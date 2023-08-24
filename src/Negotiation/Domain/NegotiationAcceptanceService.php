<?php

namespace App\Negotiation\Domain;



use App\Negotiation\Domain\Risk\RiskAssessment;
use App\Negotiation\Domain\Risk\RiskAssessmentRepository;
use App\Negotiation\Domain\Risk\RiskNegotiationValue;

class NegotiationAcceptanceService
{
    private RiskAssessmentRepository $riskAssessmentRepository;

    public function __construct(RiskAssessmentRepository $riskAssessmentRepository)
    {
        $this->riskAssessmentRepository = $riskAssessmentRepository;
    }

    public function negotiationAccepted(Negotiation $negotiation): void
    {
        $optionalRiskAssessment = $this->riskAssessmentRepository->findByNegotiator($negotiation->negotiator());
        $riskNegotiationValue = RiskNegotiationValue::fromMoney($negotiation->proposedExchangeAmount()->asMoney());

        if ($optionalRiskAssessment !== null) {
            $riskAssessment = $optionalRiskAssessment;
            $riskAssessment->addNegotiation($negotiation->negotiationId(), $riskNegotiationValue);
        } else {
            $riskAssessment = new RiskAssessment($negotiation->negotiationId(), $riskNegotiationValue, $negotiation->negotiator());
        }

        $this->riskAssessmentRepository->save($riskAssessment);
    }
}
