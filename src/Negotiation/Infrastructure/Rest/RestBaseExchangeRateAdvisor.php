<?php

namespace App\Negotiation\Infrastructure\Rest;


use App\Currency\Application\CurrencyPairApplicationService;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Negotiation\Application\BaseExchangeRateAdvisor;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestBaseExchangeRateAdvisor implements BaseExchangeRateAdvisor
{
    private HttpClientInterface $client;
    private RequestStack $requestStack;

    public function __construct(HttpClientInterface $client, RequestStack $requestStack)
    {
        $this->client = $client;
        $this->requestStack = $requestStack;
    }

    public function baseExchangeRate(Currency $baseCurrency, Currency $targetCurrency): ?BigDecimal
    {
        $getBaseUrl = $this->getBaseUrl();
        try {
            $response = $this->client->request(
                'GET',
                $getBaseUrl . "/currency-pair/{$baseCurrency->toString()}/{$targetCurrency->toString()}"
            );

            $data = $response->toArray();

            if (isset($data['adjustedExchangeRate'])) {
                return BigDecimal::fromString((string) $data['adjustedExchangeRate']);
            } elseif (isset($data['baseExchangeRate'])) {
                return BigDecimal::fromString((string) $data['baseExchangeRate']);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getBaseUrl(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request !== null) {
            return $request->getSchemeAndHttpHost();
        }
        return 'http://127.0.0.1:8000';
    }
}