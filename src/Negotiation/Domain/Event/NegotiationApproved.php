<?php

namespace App\Negotiation\Domain\Event;

use App\Kernel\Money;
use App\Negotiation\Domain\NegotiationId;
use App\Negotiation\Domain\Negotiator;

class NegotiationApproved {

    private NegotiationId $negotiationId;
    private Negotiator $negotiator;
    private Money $proposedExchangeAmount;

    public function __construct(NegotiationId $negotiationId, Negotiator $negotiator, Money $proposedExchangeAmount) {
        $this->negotiationId = $negotiationId;
        $this->negotiator = $negotiator;
        $this->proposedExchangeAmount = $proposedExchangeAmount;
    }

    public function getNegotiationId(): NegotiationId {
        return $this->negotiationId;
    }

    public function getNegotiator(): Negotiator {
        return $this->negotiator;
    }

    public function getProposedExchangeAmount(): Money {
        return $this->proposedExchangeAmount;
    }
}
