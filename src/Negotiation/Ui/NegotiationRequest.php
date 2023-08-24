<?php

namespace App\Negotiation\Ui;

class NegotiationRequest
{
    private string $identityId;
    private string $baseCurrency;
    private string $targetCurrency;
    private string $proposedExchangeAmount;
    private string $proposedExchangeCurrency;
    private string $proposedRate;

    public function __construct(string $identityId, string $baseCurrency, string $targetCurrency, string $proposedExchangeAmount, string $proposedRate, string $proposedExchangeCurrency)
    {
        $this->identityId = $identityId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->proposedExchangeAmount = $proposedExchangeAmount;
        $this->proposedRate = $proposedRate;
        $this->proposedExchangeCurrency = $proposedExchangeCurrency;
    }

    public function getIdentityId(): string
    {
        return $this->identityId;
    }

    public function setIdentityId(string $identityId): void
    {
        $this->identityId = $identityId;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function setBaseCurrency(string $baseCurrency): void
    {
        $this->baseCurrency = $baseCurrency;
    }

    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }

    public function setTargetCurrency(string $targetCurrency): void
    {
        $this->targetCurrency = $targetCurrency;
    }

    public function getProposedExchangeAmount(): string
    {
        return $this->proposedExchangeAmount;
    }

    public function setProposedExchangeAmount(string $proposedExchangeAmount): void
    {
        $this->proposedExchangeAmount = $proposedExchangeAmount;
    }

    public function getProposedRate(): string
    {
        return $this->proposedRate;
    }

    public function setProposedRate(string $proposedRate): void
    {
        $this->proposedRate = $proposedRate;
    }

    public function getProposedExchangeCurrency(): string
    {
        return $this->proposedExchangeCurrency;
    }

    public function setProposedExchangeCurrency(string $proposedExchangeCurrency): void
    {
        $this->proposedExchangeCurrency = $proposedExchangeCurrency;
    }
}

