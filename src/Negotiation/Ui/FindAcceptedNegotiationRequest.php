<?php

namespace App\Negotiation\Ui;

class FindAcceptedNegotiationRequest
{
    private string $identityId;
    private string $baseCurrency;
    private string $targetCurrency;
    private string $proposedExchangeAmount;
    private string $proposedExchangeCurrency;

    public function __construct(string $identityId, string $baseCurrency, string $targetCurrency, string $proposedExchangeAmount, string $proposedExchangeCurrency)
    {
        $this->identityId = $identityId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->proposedExchangeAmount = $proposedExchangeAmount;
        $this->proposedExchangeCurrency = $proposedExchangeCurrency;
    }

    public function getIdentityId()
    {
        return $this->identityId;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }

    public function getProposedExchangeAmount(): string
    {
        return $this->proposedExchangeAmount;
    }

    public function getProposedExchangeCurrency(): string
    {
        return $this->proposedExchangeCurrency;
    }
}