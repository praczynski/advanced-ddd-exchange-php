<?php

namespace App\Negotiation\Domain;

use App\Negotiation\Domain\Event\NegotiationApproved;
use App\Negotiation\Domain\Event\NegotiationCreated;

interface NegotiationDomainEventBus
{
    function postNegotiationCreated(NegotiationCreated $event): void;
    function postNegotiationApproved(NegotiationApproved $event): void;
}
