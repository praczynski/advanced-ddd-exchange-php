<?php

namespace App\Account\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class DepositFundCommand {
    private string $accountNumber;
    private BigDecimal $fundsToDeposit;
    private Currency $currency;

    public function __construct(string $accountNumber, BigDecimal $fundsToDeposit, Currency $currency) {
        $this->accountNumber = $accountNumber;
        $this->fundsToDeposit = $fundsToDeposit;
        $this->currency = $currency;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getFundsToDeposit(): BigDecimal
    {
        return $this->fundsToDeposit;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
