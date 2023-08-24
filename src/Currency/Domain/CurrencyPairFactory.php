<?php

namespace App\Currency\Domain;

use App\Currency\Domain\Exception\CurrencyPairNotSupportedException;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class CurrencyPairFactory {

    private BaseCurrencyPairRate $baseCurrencyPairRate;

    public function __construct(BaseCurrencyPairRate $baseCurrencyPairRate) {
        $this->baseCurrencyPairRate = $baseCurrencyPairRate;
    }

    public function create(Currency $baseCurrency, Currency $targetCurrency): CurrencyPair {

        $optionalExchangeRate = $this->baseCurrencyPairRate->baseRateFor($baseCurrency, $targetCurrency);


        $pairId = CurrencyPairId::generate();

        if (!$optionalExchangeRate) {
            throw new CurrencyPairNotSupportedException($baseCurrency, $targetCurrency);
        }

        return new CurrencyPair($pairId, $baseCurrency, $targetCurrency, $optionalExchangeRate);
    }

    public function createWithAdjustedRate(BigDecimal $adjustedRate, Currency $baseCurrency, Currency $targetCurrency): CurrencyPair {
        $optionalExchangeRate = $this->baseCurrencyPairRate->baseRateFor($baseCurrency, $targetCurrency);

        if (!$optionalExchangeRate) {
            throw new CurrencyPairNotSupportedException($baseCurrency, $targetCurrency);
        }

        $exchangeRate = $optionalExchangeRate->adjust($adjustedRate);
        $pairId = CurrencyPairId::generate();

        return new CurrencyPair($pairId, $baseCurrency, $targetCurrency, $exchangeRate);
    }
}
