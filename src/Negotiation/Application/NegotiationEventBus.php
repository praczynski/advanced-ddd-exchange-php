<?php

namespace App\Negotiation\Application;


use App\Negotiation\Domain\Event\NegotiationApproved;
use App\Negotiation\Domain\Event\NegotiationCreated;
use App\Negotiation\Domain\NegotiationDomainEventBus;

class NegotiationEventBus implements NegotiationDomainEventBus
{
    private RiskAssessmentApplicationService $riskAssessmentApplicationService;

    public function __construct(RiskAssessmentApplicationService $riskAssessmentApplicationService)
    {
        $this->riskAssessmentApplicationService = $riskAssessmentApplicationService;
    }

    public function postNegotiationCreated(NegotiationCreated $event): void
    {
    }

    public function postNegotiationApproved(NegotiationApproved $event): void
    {
        $this->riskAssessmentApplicationService->negotiationApproved(
            $event->getNegotiationId(),
            $event->getNegotiator(),
            $event->getProposedExchangeAmount()
        );
    }
}