<?php

namespace App\Quoting\Domain;

use App\Kernel\Currency;

interface ExchangeRateAdvisor {

    function exchangeRate(Requester $requester, MoneyToExchange $moneyToExchange, Currency $currencyToSell, Currency $currencyToBuy): ?ExchangeRate;
}
