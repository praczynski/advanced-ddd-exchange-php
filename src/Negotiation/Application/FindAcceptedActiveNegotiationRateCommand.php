<?php

namespace App\Negotiation\Application;


use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;

class FindAcceptedActiveNegotiationRateCommand
{
    private IdentityId $identityId;
    private Currency $baseCurrency;
    private Currency $targetCurrency;
    private BigDecimal $proposedExchangeAmount;
    private Currency $proposedExchangeCurrency;

    public function __construct(IdentityId $identityId, Currency $baseCurrency,Currency $targetCurrency, BigDecimal $proposedExchangeAmount, Currency $proposedExchangeCurrency)
    {
        $this->identityId = $identityId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->proposedExchangeAmount = $proposedExchangeAmount;
        $this->proposedExchangeCurrency = $proposedExchangeCurrency;
    }

    public function getIdentityId(): IdentityId
    {
        return $this->identityId;
    }

    public function setIdentityId(IdentityId $identityId): void
    {
        $this->identityId = $identityId;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function setBaseCurrency(Currency $baseCurrency): void
    {
        $this->baseCurrency = $baseCurrency;
    }

    public function getTargetCurrency(): Currency
    {
        return $this->targetCurrency;
    }

    public function setTargetCurrency(Currency $targetCurrency): void
    {
        $this->targetCurrency = $targetCurrency;
    }

    public function getProposedExchangeAmount(): BigDecimal
    {
        return $this->proposedExchangeAmount;
    }

    public function setProposedExchangeAmount(BigDecimal $proposedExchangeAmount): void
    {
        $this->proposedExchangeAmount = $proposedExchangeAmount;
    }

    public function getProposedExchangeCurrency(): Currency
    {
        return $this->proposedExchangeCurrency;
    }

    public function setProposedExchangeCurrency(Currency $proposedExchangeCurrency): void
    {
        $this->proposedExchangeCurrency = $proposedExchangeCurrency;
    }
}