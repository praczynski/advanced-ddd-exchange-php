<?php

namespace App\Currency\Ui;

class CurrencyPairWithRateRequest {

    private string $baseCurrency;
    private string $targetCurrency;
    private string $adjustedRate;

    public function __construct() {}

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

    public function getAdjustedRate(): string {
        return $this->adjustedRate;
    }

    public function setAdjustedRate(string $adjustedRate): void {
        $this->adjustedRate = $adjustedRate;
    }
}

