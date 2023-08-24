<?php

namespace App\Negotiation\Domain;

class AutomaticNegotiationStatus
{
    private const PENDING = "PENDING";
    private const APPROVED = "APPROVED";
    private const REJECTED_TOO_SMALL_AMOUNT = "REJECTED_TOO_SMALL_AMOUNT";
    private const REJECTED = "REJECTED";

    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function PENDING(): self
    {
        return new self(self::PENDING);
    }

    public static function APPROVED(): self
    {
        return new self(self::APPROVED);
    }

    public static function REJECTED_TOO_SMALL_AMOUNT(): self
    {
        return new self(self::REJECTED_TOO_SMALL_AMOUNT);
    }

    public static function REJECTED(): self
    {
        return new self(self::REJECTED);
    }

    public function isApproved(): bool
    {
        return $this->status === self::APPROVED;
    }

    public function equals(AutomaticNegotiationStatus $other): bool
    {
        return $this->status === $other->getStatus();
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
