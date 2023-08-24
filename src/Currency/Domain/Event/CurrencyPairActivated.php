<?php

namespace App\Currency\Domain\Event;


use App\Kernel\Currency;

class CurrencyPairActivated
{
    private Currency $baseCurrency;
    private Currency $targetCurrency;

    public function __construct(Currency $baseCurrency, Currency $targetCurrency)
    {
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): Currency
    {
        return $this->targetCurrency;
    }
}
