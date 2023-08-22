<?php

namespace App\Account\Application;

use App\Kernel\BigDecimal\BigDecimal;

class ExchangeRateCommand {

    private string $currencyToBuy;
    private string $currencyToSell;
    private BigDecimal $rate;

    public function __construct(string $currencyToBuy, string $currencyToSell, BigDecimal $rate) {
        $this->currencyToBuy = $currencyToBuy;
        $this->currencyToSell = $currencyToSell;
        $this->rate = $rate;
    }

    public function getCurrencyToBuy(): string {
        return $this->currencyToBuy;
    }

    public function getCurrencyToSell(): string {
        return $this->currencyToSell;
    }

    public function getRate(): BigDecimal {
        return $this->rate;
    }
}