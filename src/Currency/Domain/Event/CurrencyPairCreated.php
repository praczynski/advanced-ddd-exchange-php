<?php

namespace App\Currency\Domain\Event;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class CurrencyPairCreated
{
    private Currency $baseCurrency;
    private Currency $targetCurrency;
    private BigDecimal $rate;

    public function __construct(Currency $baseCurrency, Currency $targetCurrency, BigDecimal $rate)
    {
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->rate = $rate;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): Currency
    {
        return $this->targetCurrency;
    }

    public function getRate(): BigDecimal
    {
        return $this->rate;
    }
}
