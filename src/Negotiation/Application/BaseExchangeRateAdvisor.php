<?php

namespace App\Negotiation\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

interface BaseExchangeRateAdvisor
{
    public function baseExchangeRate(Currency $baseCurrency, Currency $targetCurrency): ?BigDecimal;
}