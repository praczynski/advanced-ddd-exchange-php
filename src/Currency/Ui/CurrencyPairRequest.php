<?php

namespace App\Currency\Ui;

class CurrencyPairRequest {

    private string $baseCurrency;
    private string $targetCurrency;

    public function getBaseCurrency(): string {
        return $this->baseCurrency;
    }

    public function setBaseCurrency(string $baseCurrency): void {
        $this->baseCurrency = $baseCurrency;
    }

    public function getTargetCurrency(): string {
        return $this->targetCurrency;
    }

    public function setTargetCurrency(string $targetCurrency): void {
        $this->targetCurrency = $targetCurrency;
    }
}
