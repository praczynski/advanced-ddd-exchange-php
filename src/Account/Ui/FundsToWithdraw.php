<?php

namespace App\Account\Ui;


use App\Kernel\BigDecimal\BigDecimal;

class FundsToWithdraw
{
    private string $value;
    private string $currency;

    public function __construct(string $value, string $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}