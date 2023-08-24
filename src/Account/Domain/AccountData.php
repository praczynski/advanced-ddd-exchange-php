<?php

namespace App\Account\Domain;


class AccountData {
    private string $accountNumber;
    private string $traderNumber;
    private array $wallets;

    public function __construct(string $accountNumber, string $traderNumber, array $wallets) {
        $this->accountNumber = $accountNumber;
        $this->traderNumber = $traderNumber;
        $this->wallets = $wallets;
    }

    public function getAccountNumber(): string {
        return $this->accountNumber;
    }

    public function getTraderNumber(): string {
        return $this->traderNumber;
    }

    public function getWallets(): array {
        return $this->wallets;
    }

    public function setWallets(array $wallets): void {
        $this->wallets = $wallets;
    }
}
