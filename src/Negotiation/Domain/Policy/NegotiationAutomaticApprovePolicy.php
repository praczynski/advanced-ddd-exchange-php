<?php

namespace App\Negotiation\Domain\Policy;

use App\Kernel\BigDecimal\BigDecimal;
use App\Negotiation\Domain\ProposedExchangeAmount;

interface NegotiationAutomaticApprovePolicy {
    function shouldApprove(ProposedExchangeAmount $proposedExchangeAmount, BigDecimal $percent): bool;
    function isApplicable(ProposedExchangeAmount $proposedExchangeAmount): bool;
}