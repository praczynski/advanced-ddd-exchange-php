<?php

namespace App\Account\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class DepositFundsByCardCommand
{
    private string $traderNumber;
    private BigDecimal $fundsToDeposit;
    private Currency $currency;

    public function __construct(string $traderNumber, BigDecimal $fundsToDeposit, Currency $currency)
    {
        $this->traderNumber = $traderNumber;
        $this->fundsToDeposit = $fundsToDeposit;
        $this->currency = $currency;
    }

    public function getTraderNumber(): string
    {
        return $this->traderNumber;
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