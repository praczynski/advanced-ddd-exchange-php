<?php

namespace App\Negotiation\Application;

use App\Kernel\Money;
use App\Negotiation\Domain\NegotiationId;
use App\Negotiation\Domain\Negotiator;
use App\Negotiation\Domain\Risk\RiskAssessment;
use App\Negotiation\Domain\Risk\RiskAssessmentNumber;
use App\Negotiation\Domain\Risk\RiskAssessmentRepository;
use App\Negotiation\Domain\Risk\RiskLevel;
use App\Negotiation\Domain\Risk\RiskNegotiationValue;

class RiskAssessmentApplicationService {

    private RiskAssessmentRepository $riskAssessmentRepository;

    public function __construct(RiskAssessmentRepository $riskAssessmentRepository) {
        $this->riskAssessmentRepository = $riskAssessmentRepository;
    }

    public function changeRiskAssessmentRiskLevel(string $riskAssessmentNumber, string $riskLevel): ChangeRiskAssessmentRiskLevelStatus {
        $byRiskAssessmentNumber = $this->riskAssessmentRepository->findByRiskAssessmentNumber(RiskAssessmentNumber::fromString($riskAssessmentNumber));

        if (null !== $byRiskAssessmentNumber) {
            $byRiskAssessmentNumber->changeRiskLevel(new RiskLevel($riskLevel));
            return new ChangeRiskAssessmentRiskLevelStatus("OK");
        }

        return new ChangeRiskAssessmentRiskLevelStatus("Risk assessment not found");
    }

    public function negotiationApproved(NegotiationId $negotiationId, Negotiator $negotiator, Money $proposedExchangeAmount): CreateRiskAssessmentStatus {
        $optionalRiskAssessment = $this->riskAssessmentRepository->findByNegotiator($negotiator);
        $riskNegotiationValue = new RiskNegotiationValue($proposedExchangeAmount);

        if (null !== $optionalRiskAssessment) {
            $optionalRiskAssessment->addNegotiation($negotiationId, $riskNegotiationValue);
        } else {
            $optionalRiskAssessment = new RiskAssessment($negotiationId, $riskNegotiationValue, $negotiator);
        }

        $this->riskAssessmentRepository->save($optionalRiskAssessment);
        return CreateRiskAssessmentStatus::create("OK");
    }
}
