<?php

namespace App\Account\Application;

class DepositFundsStatus {

    private string $status;
    private ?string $accountNumber;

    private function __construct(string $status, ?string $accountNumber = null) {
        $this->status = $status;
        $this->accountNumber = $accountNumber;
    }

    public static function SUCCESS(string $accountId): DepositFundsStatus
    {
        return new DepositFundsStatus("Success", $accountId);
    }

    public static function TRANSACTION_LIMIT_EXCEEDED(): DepositFundsStatus
    {
        return new DepositFundsStatus("TRANSACTION_LIMIT_EXCEEDED");
    }
    public static function ACCOUNT_NOT_FOUND(): DepositFundsStatus
    {
        return new DepositFundsStatus("ACCOUNT_NOT_FOUND");
    }
    public static function WALLET_NOT_FOUND(): DepositFundsStatus
    {
        return new DepositFundsStatus("WALLET_NOT_FOUND");
    }
    public static function WALLETS_LIMIT_EXCEEDED(): DepositFundsStatus
    {
        return new DepositFundsStatus("WALLETS_LIMIT_EXCEEDED");
    }

    public function getStatus() {
        return $this->status;
    }
}

