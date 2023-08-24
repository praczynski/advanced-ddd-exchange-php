<?php

namespace App\Account\Application;


use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class WithdrawFundsCommand
{
    private string $traderNumber;
    private BigDecimal $fundsToWithdraw;
    private Currency $currency;

    public function __construct(string $traderNumber, BigDecimal $fundsToWithdraw, Currency $currency)
{
    $this->traderNumber = $traderNumber;
    $this->fundsToWithdraw = $fundsToWithdraw;
    $this->currency = $currency;
}

    public function getTraderNumber(): string
{
    return $this->traderNumber;
}

    public function getFundsToWithdraw(): BigDecimal
{
    return $this->fundsToWithdraw;
}

    public function getCurrency(): Currency
{
    return $this->currency;
}
}