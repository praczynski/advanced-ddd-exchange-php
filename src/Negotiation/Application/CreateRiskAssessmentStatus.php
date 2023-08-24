<?php

namespace App\Negotiation\Application;

class CreateRiskAssessmentStatus
{
    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function create(string $status): self
    {
        return new self($status);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}