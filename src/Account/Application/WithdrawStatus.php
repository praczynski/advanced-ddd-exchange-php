<?php

namespace App\Account\Application;

class WithdrawStatus
{
    private const INSUFFICIENT_FUNDS = "INSUFFICIENT_FUNDS";
    private const WITHDRAW_SUCCESS = "WITHDRAW_SUCCESS";
    private const TRANSACTION_LIMIT_EXCEEDED = "TRANSACTION_LIMIT_EXCEEDED";
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

    public static function WITHDRAW_SUCCESS(): self
    {
        return new self(self::WITHDRAW_SUCCESS);
    }

    public static function TRANSACTION_LIMIT_EXCEEDED(): self
    {
        return new self(self::TRANSACTION_LIMIT_EXCEEDED);
    }

    public static function ACCOUNT_NOT_FOUND(): self
    {
        return new self(self::ACCOUNT_NOT_FOUND);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}