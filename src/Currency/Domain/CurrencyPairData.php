<?php

namespace App\Currency\Domain;

use App\Kernel\BigDecimal\BigDecimal;
use Ramsey\Uuid\UuidInterface;

class CurrencyPairData
{
    private string $baseCurrency;
    private string $targetCurrency;
    private string $baseExchangeRate;
    private string $adjustedExchangeRate;
    private UuidInterface $currencyPairId;


    public function __construct(UuidInterface $currencyPairId, string $baseCurrency, string $targetCurrency, string $baseExchangeRate, string $adjustedExchangeRate)
    {
        $this->currencyPairId = $currencyPairId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->baseExchangeRate = $baseExchangeRate;
        $this->adjustedExchangeRate = $adjustedExchangeRate;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }

    public function getCurrencyPairId(): UuidInterface
    {
        return $this->currencyPairId;
    }

    public function setBaseCurrency(string $baseCurrency): void
    {
        $this->baseCurrency = $baseCurrency;
    }

    public function setTargetCurrency(string $targetCurrency): void
    {
        $this->targetCurrency = $targetCurrency;
    }

    public function getBaseExchangeRate(): string
    {
        return $this->baseExchangeRate;
    }

    public function setBaseExchangeRate(string $baseExchangeRate): void
    {
        $this->baseExchangeRate = $baseExchangeRate;
    }

    public function getAdjustedExchangeRate(): string
    {
        return $this->adjustedExchangeRate;
    }

    public function setAdjustedExchangeRate(string $adjustedExchangeRate): void
    {
        $this->adjustedExchangeRate = $adjustedExchangeRate;
    }

    public function setCurrencyPairId(UuidInterface $currencyPairId): void
    {
        $this->currencyPairId = $currencyPairId;
    }
}
