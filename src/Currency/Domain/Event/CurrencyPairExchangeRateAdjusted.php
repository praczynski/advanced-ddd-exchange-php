<?php

namespace App\Currency\Domain\Event;

use App\Currency\Domain\CurrencyPairId;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class CurrencyPairExchangeRateAdjusted
{
    private CurrencyPairId $currencyPairId;
    private Currency $baseCurrency;
    private Currency $targetCurrency;
    private BigDecimal $adjustedRate;

    public function __construct(
        CurrencyPairId $currencyPairId,
        Currency $baseCurrency,
        Currency $targetCurrency,
        BigDecimal $adjustedRate
    ) {
        $this->currencyPairId = $currencyPairId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->adjustedRate = $adjustedRate;
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

    public function getAdjustedRate(): BigDecimal
    {
        return $this->adjustedRate;
    }
}
