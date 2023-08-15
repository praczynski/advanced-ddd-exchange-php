<?php

namespace App\Account\Domain;

use Brick\Math\BigDecimal;

class WalletData {
    private string $walletIdString;
    private WalletId $walletId;
    private string $funds;
    private BigDecimal $fundsValue;
    private string $fundsCurrency;

    public function __construct(WalletId $walletId, BigDecimal $fundsValue, string $fundsCurrency) {
        $this->walletId = $walletId;
        $this->walletIdString = $walletId->toString();
        $this->funds = $fundsValue . ' ' . $fundsCurrency;
        $this->fundsValue = $fundsValue;
        $this->fundsCurrency = $fundsCurrency;
    }

    public function getWalletIdString(): string
    {
        return $this->walletIdString;
    }

    public function getFundsValue(): BigDecimal
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

    public function setFundsValue(BigDecimal $fundsValue): void
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