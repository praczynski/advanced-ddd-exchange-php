<?php

namespace App\Account\Ui;


use App\Kernel\BigDecimal\BigDecimal;

class TransferFundsBetweenWalletsRequest
{
    private string $moneyToBuyValue;
    private string $moneyToBuyCurrency;
    private string $moneyToSellValue;
    private string $moneyToSellCurrency;

    public function __construct(string $moneyToBuyValue, string $moneyToBuyCurrency, string $moneyToSellValue, string $moneyToSellCurrency)
    {
        $this->moneyToBuyValue = $moneyToBuyValue;
        $this->moneyToBuyCurrency = $moneyToBuyCurrency;
        $this->moneyToSellValue = $moneyToSellValue;
        $this->moneyToSellCurrency = $moneyToSellCurrency;
    }

    public function getMoneyToBuyValue(): string
    {
        return $this->moneyToBuyValue;
    }

    public function getMoneyToBuyCurrency(): string
    {
        return $this->moneyToBuyCurrency;
    }

    public function getMoneyToSellValue(): string
    {
        return $this->moneyToSellValue;
    }

    public function getMoneyToSellCurrency(): string
    {
        return $this->moneyToSellCurrency;
    }
}
