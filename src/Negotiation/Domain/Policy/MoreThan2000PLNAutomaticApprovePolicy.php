<?php

namespace App\Negotiation\Domain\Policy;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Negotiation\Domain\ProposedExchangeAmount;

class MoreThan2000PLNAutomaticApprovePolicy implements NegotiationAutomaticApprovePolicy
{

    private ProposedExchangeAmount $MIN_AMOUNT;

    public function __construct()
    {
        $this->MIN_AMOUNT = ProposedExchangeAmount::fromValueAndCurrency(BigDecimal::fromString("2000"), new Currency("PLN"));
    }


    public function shouldApprove(ProposedExchangeAmount $proposedExchangeAmount, BigDecimal $percent): bool
    {

        return $proposedExchangeAmount->isMoreOrEquals($this->MIN_AMOUNT) && $percent->compareTo(BigDecimal::fromString("10")) < 0;
    }

    public function isApplicable(ProposedExchangeAmount $proposedExchangeAmount): bool
    {
        return $proposedExchangeAmount->theSameCurrency(new Currency("PLN"));
    }
}