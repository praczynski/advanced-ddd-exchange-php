<?php

namespace App\Account\Application;


use App\Kernel\BigDecimal\BigDecimal;

class TransferFundsBetweenAccountCommand
{
    private string $fromAccountId;
    private string $toAccountId;
    private BigDecimal $fundsToTransfer;
    private string $currency;

    public function __construct(string $fromAccountId, string $toAccountId, BigDecimal $fundsToTransfer, string $currency)
    {
        $this->fromAccountId = $fromAccountId;
        $this->toAccountId = $toAccountId;
        $this->fundsToTransfer = $fundsToTransfer;
        $this->currency = $currency;
    }

    public function getFromAccountId(): string
    {
        return $this->fromAccountId;
    }

    public function getToAccountId(): string
    {
        return $this->toAccountId;
    }

    public function getFundsToTransfer(): BigDecimal
    {
        return $this->fundsToTransfer;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}