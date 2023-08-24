<?php

namespace App\Quoting\Infrastructure;

use App\Kernel\Currency;
use App\Quoting\Domain\ExchangeRate;
use App\Quoting\Domain\ExchangeRateAdvisor;
use App\Quoting\Domain\MoneyToExchange;
use App\Quoting\Domain\Requester;

class SubscriptionExchangeRateAdvisor implements ExchangeRateAdvisor
{

    function exchangeRate(Requester $requester, MoneyToExchange $moneyToExchange, Currency $currencyToSell, Currency $currencyToBuy): ?ExchangeRate
    {
        return null;
    }
}
