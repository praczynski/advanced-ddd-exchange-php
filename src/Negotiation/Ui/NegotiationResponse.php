<?php

namespace App\Negotiation\Ui;

class NegotiationResponse
{
    private ?string $rate;
    private ?string $status;

    private function __construct(?string $rate)
    {
        if ($rate === null) {
            $this->status = "CANNOT_FIND_RATE";
        } else {
            $this->rate = $rate;
            $this->status = "SUCCESS";
        }
    }

    public static function create(?string $rate): self
    {
        return new self($rate);
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): void
    {
        $this->rate = $rate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}