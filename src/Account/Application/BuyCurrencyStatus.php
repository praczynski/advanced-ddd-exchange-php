<?php

namespace App\Account\Application;

class BuyCurrencyStatus
{
    private const INSUFFICIENT_FUNDS = "INSUFFICIENT_FUNDS";
    private const BUY_SUCCESS = "BUY_SUCCESS";
    private const ACCOUNT_NOT_FOUND = "ACCOUNT_NOT_FOUND";

    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function INSUFFICIENT_FUNDS(): self
    {
        return new self(self::INSUFFICIENT_FUNDS);
    }

    public static function BUY_SUCCESS(): self
    {
        return new self(self::BUY_SUCCESS);
    }

    public static function ACCOUNT_NOT_FOUND(): self
    {
        return new self(self::ACCOUNT_NOT_FOUND);
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