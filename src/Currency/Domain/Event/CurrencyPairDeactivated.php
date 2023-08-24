<?php

namespace App\Currency\Domain\Event;


use App\Currency\Domain\CurrencyPairId;
use App\Kernel\Currency;

class CurrencyPairDeactivated
{
    private CurrencyPairId $currencyPairId;
    private Currency $baseCurrency;
    private Currency $targetCurrency;

    public function __construct(CurrencyPairId $currencyPairId, Currency $baseCurrency, Currency $targetCurrency)
    {
        $this->currencyPairId = $currencyPairId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
    }

    public function getCurrencyPairId(): CurrencyPairId
    {
        return $this->currencyPairId;
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
