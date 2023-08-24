<?php

namespace App\Negotiation\Application;

class CreateNegotiationStatus
{
    private const PENDING = 'PENDING';
    private const APPROVED = 'APPROVED';
    private const REJECTED_TOO_SMALL_AMOUNT = 'REJECTED_TOO_SMALL_AMOUNT';
    private const REJECTED = 'REJECTED';
    private const ALREADY_EXISTS = 'ALREADY_EXISTS';
    private const CURRENCY_PAIR_NOT_SUPPORTED = 'CURRENCY_PAIR_NOT_SUPPORTED';

    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
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

    public static function ALREADY_EXISTS(): self
    {
        return new self(self::ALREADY_EXISTS);
    }

    public static function CURRENCY_PAIR_NOT_SUPPORTED(): self
    {
        return new self(self::CURRENCY_PAIR_NOT_SUPPORTED);
    }
}
