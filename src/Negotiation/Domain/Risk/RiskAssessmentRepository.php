<?php

namespace App\Negotiation\Domain\Risk;

use App\Negotiation\Domain\Negotiator;


interface RiskAssessmentRepository
{
    public function save(RiskAssessment $riskAssessment): void;

    public function findByNegotiator(Negotiator $negotiator): ?RiskAssessment;

    public function findByRiskAssessmentNumber(RiskAssessmentNumber $riskAssessmentNumber): ?RiskAssessment;
}

