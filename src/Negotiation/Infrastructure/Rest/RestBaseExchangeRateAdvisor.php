<?php

namespace App\Negotiation\Infrastructure\Rest;


use App\Currency\Application\CurrencyPairApplicationService;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Negotiation\Application\BaseExchangeRateAdvisor;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestBaseExchangeRateAdvisor implements BaseExchangeRateAdvisor
{

    private const ENDPOINT = "http://127.0.0.1:8000/currency-pair";
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function baseExchangeRate(Currency $baseCurrency, Currency $targetCurrency): ?BigDecimal
    {
        try {
            $response = $this->client->request(
                'GET',
                self::ENDPOINT . "/{$baseCurrency->toString()}/{$targetCurrency->toString()}"
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
}