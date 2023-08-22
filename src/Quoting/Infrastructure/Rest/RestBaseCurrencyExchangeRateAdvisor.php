<?php

namespace App\Quoting\Infrastructure\Rest;

use App\Currency\Application\CurrencyPairApplicationService;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Quoting\Domain\ExchangeRate;
use App\Quoting\Domain\ExchangeRateAdvisor;
use App\Quoting\Domain\MoneyToExchange;
use App\Quoting\Domain\Rate;
use App\Quoting\Domain\Requester;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class RestBaseCurrencyExchangeRateAdvisor implements ExchangeRateAdvisor
{
    private const ENDPOINT = "https://v6.exchangerate-api.com/v6/86c982b631b2df47540aabc4";
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    function exchangeRate(Requester $requester, MoneyToExchange $moneyToExchange, Currency $currencyToSell, Currency $currencyToBuy): ?ExchangeRate
    {
        try {
            $response = $this->client->request(
                'GET',
                self::ENDPOINT . "/pair/{$currencyToSell->toString()}/{$currencyToBuy->toString()}"
            );

            $data = $response->toArray();
            $value = number_format($data['conversion_rate'], 2);


            return ExchangeRate::create($currencyToSell, $currencyToBuy, Rate::fromString($value));
        } catch (Exception $e) {
            return null;
        }
    }
}
