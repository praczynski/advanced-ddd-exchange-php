<?php

namespace App\Negotiation\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;

class CreateNegotiationCommand
{
    private IdentityId $identityId;
    private Currency $baseCurrency;
    private Currency $targetCurrency;
    private BigDecimal $proposedExchangeAmount;
    private Currency $proposedExchangeCurrency;
    private BigDecimal $proposedRate;

    public function __construct(
        IdentityId $identityId,
        Currency $baseCurrency,
        Currency $targetCurrency,
        BigDecimal $proposedExchangeAmount,
        Currency $proposedExchangeCurrency,
        BigDecimal $proposedRate
    ) {
        $this->identityId = $identityId;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->proposedExchangeAmount = $proposedExchangeAmount;
        $this->proposedExchangeCurrency = $proposedExchangeCurrency;
        $this->proposedRate = $proposedRate;
    }

    public function getIdentityId(): IdentityId
    {
        return $this->identityId;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): Currency
    {
        return $this->targetCurrency;
    }

    public function getProposedExchangeAmount(): BigDecimal
    {
        return $this->proposedExchangeAmount;
    }

    public function getProposedExchangeCurrency(): Currency
    {
        return $this->proposedExchangeCurrency;
    }

    public function getProposedRate(): BigDecimal
    {
        return $this->proposedRate;
    }
}