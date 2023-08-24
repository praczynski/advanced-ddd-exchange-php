<?php

namespace App\Currency\Infrastructure\Rest;

use App\Currency\Domain\BaseCurrencyPairRate;
use App\Currency\Domain\ExchangeRate;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestBaseCurrencyPairRate implements BaseCurrencyPairRate {

    private const ENDPOINT = "https://v6.exchangerate-api.com/v6/86c982b631b2df47540aabc4";
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function baseRateFor(Currency $baseCurrency, Currency $targetCurrency): ?ExchangeRate
    {
        try {
            $response = $this->client->request(
                'GET',
                self::ENDPOINT . "/pair/{$baseCurrency->toString()}/{$targetCurrency->toString()}"
            );

            $data = $response->toArray();
            $value = number_format($data['conversion_rate'], 2);

            return ExchangeRate::withBaseRate( BigDecimal::fromString($value));
        } catch (Exception $e) {
            return null;
        }
    }
}
