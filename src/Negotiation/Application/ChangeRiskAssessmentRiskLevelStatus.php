<?php

namespace App\Negotiation\Application;

class ChangeRiskAssessmentRiskLevelStatus {
    private string $status;

    public function __construct(string $status) {
        $this->status = $status;
    }

    public function getStatus(): string {
        return $this->status;
    }
}