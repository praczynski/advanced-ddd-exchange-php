<?php

namespace App\Currency\Application;

use App\Kernel\BigDecimal\BigDecimal;

class CurrencyPairResponse {
    private string $baseCurrency;
    private string $targetCurrency;
    private string $baseExchangeRate;
    private ?string $adjustedExchangeRate;
    private string $currencyPairId;
    private string $status;

    public function __construct(string $currencyPairId, string $baseCurrency, string $targetCurrency, string $baseExchangeRate, ?string $adjustedExchangeRate = null)
    {
        $this->currencyPairId = $currencyPairId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->baseExchangeRate = $baseExchangeRate;
        $this->adjustedExchangeRate = $adjustedExchangeRate;
        $this->status = "SUCCESS";
    }

    public static function failure(): self
    {
        $instance = new self('', '', '', '');
        $instance->status = "FAILURE";
        return $instance;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getBaseCurrency(): string {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): string {
        return $this->targetCurrency;
    }

    public function getCurrencyPairId(): string {
        return $this->currencyPairId;
    }

    public function setBaseCurrency(string $baseCurrency): void {
        $this->baseCurrency = $baseCurrency;
    }

    public function setTargetCurrency(string $targetCurrency): void {
        $this->targetCurrency = $targetCurrency;
    }

    public function getBaseExchangeRate(): string {
        return $this->baseExchangeRate;
    }

    public function setBaseExchangeRate(string $baseExchangeRate): void {
        $this->baseExchangeRate = $baseExchangeRate;
    }

    public function getAdjustedExchangeRate(): ?string {
        return $this->adjustedExchangeRate;
    }

    public function setAdjustedExchangeRate(?string $adjustedExchangeRate): void {
        $this->adjustedExchangeRate = $adjustedExchangeRate;
    }

    public function setCurrencyPairId(string $currencyPairId): void {
        $this->currencyPairId = $currencyPairId;
    }

    public function getExchangeRate(): string {
        return $this->adjustedExchangeRate ?? $this->baseExchangeRate;
    }
}
