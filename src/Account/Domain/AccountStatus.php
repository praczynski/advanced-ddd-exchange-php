<?php

namespace App\Account\Domain;

class AccountStatus {
    public const SUCCESS = "SUCCESS";
    public const ACCOUNT_ALREADY_EXISTS = "ACCOUNT_ALREADY_EXISTS";

    private ?Account $account;
    private ?string $accountNumber;
    private ?string $traderNumber;
    private string $createStatus;

    private function __construct(string $status, ?Account $account, ?string $accountNumber, ?string $traderNumber)
    {
        $this->createStatus = $status;
        $this->account = $account;
        $this->accountNumber = $accountNumber;
        $this->traderNumber = $traderNumber;
    }

    public static function createSuccessAccountStatus(string $status, Account $account, string $accountId, string $traderNumber): self
    {
        return new self($status, $account, $accountId, $traderNumber);
    }

    public static function createAccountAlreadyExistsStatus(): self
    {
        return new self(self::ACCOUNT_ALREADY_EXISTS, null, null, null);
    }

    public function isSuccess(): bool
    {
        return $this->createStatus === self::SUCCESS;
    }

    public function account(): ?Account
    {
        return $this->account;
    }

    public function accountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function traderNumber(): ?string
    {
        return $this->traderNumber;
    }

    public function status(): string
    {
        return $this->createStatus;
    }

}
