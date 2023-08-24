<?php

namespace App\Currency\Domain;

use App\Kernel\Currency;

interface BaseCurrencyPairRate
{
    public function baseRateFor(Currency $baseCurrency, Currency $targetCurrency): ?ExchangeRate;
}
