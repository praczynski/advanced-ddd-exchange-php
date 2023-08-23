<?php

namespace App\Account\Domain;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use Exception;
use http\Exception\RuntimeException;

class ExchangeRate {
    private Currency $currencyToSell;
    private Currency $currencyToBuy;
    private BigDecimal $rate;

    public function __construct(Currency $currencyToSell, Currency $currencyToBuy, BigDecimal $rate) {
        $this->currencyToSell = $currencyToSell;
        $this->currencyToBuy = $currencyToBuy;
        $this->rate = $rate;
    }

    public function calculate(Funds $value): Funds
    {
        if (!$value->isSameCurrency($this->currencyToBuy)) {
            throw new Exception("Different currencies");
        }
        return $value->multiply($this->rate, $this->currencyToSell);
    }
}
