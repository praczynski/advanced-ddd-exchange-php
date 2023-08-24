<?php

namespace App\Account\Domain;

class WalletData {
    private string $walletIdString;
    private WalletId $walletId;
    private string $funds;
    private string $fundsValue;
    private string $fundsCurrency;

    public function __construct(WalletId $walletId, string $fundsValue, string $fundsCurrency) {
        $this->walletId = $walletId;
        $this->walletIdString = $walletId->__toString();
        $this->funds = $fundsValue . ' ' . $fundsCurrency;
        $this->fundsValue = $fundsValue;
        $this->fundsCurrency = $fundsCurrency;
    }

    public function getWalletIdString(): string
    {
        return $this->walletIdString;
    }

    public function getFundsValue(): string
    {
        return $this->fundsValue;
    }

    public function getFundsCurrency(): string
    {
        return $this->fundsCurrency;
    }

    public function setWalletId(WalletId $walletId): void
    {
        $this->walletId = $walletId;
    }

    public function setWalletIdString(string $walletIdString): void
    {
        $this->walletIdString = $walletIdString;
    }

    public function setFundsValue(string $fundsValue): void
    {
        $this->fundsValue = $fundsValue;
    }

    public function setFundsCurrency(string $fundsCurrency): void
    {
        $this->fundsCurrency = $fundsCurrency;
    }

    public function getFunds(): string
    {
        return $this->funds;
    }

    public function setFunds(string $funds): void
    {
        $this->funds = $funds;
    }
}