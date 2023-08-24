<?php

namespace App\Quoting\Ui;

class PrepareQuoteRequest
{
    private string $identityId;
    private string $moneyToExchangeValue;
    private string $moneyToExchangeCurrency;
    private string $currencyToBuy;
    private string $currencyToSell;

    public function __construct(
        string $identityId,
        string $moneyToExchangeValue,
        string $moneyToExchangeCurrency,
        string $currencyToBuy,
        string $currencyToSell
    ) {
        $this->identityId = $identityId;
        $this->moneyToExchangeValue = $moneyToExchangeValue;
        $this->moneyToExchangeCurrency = $moneyToExchangeCurrency;
        $this->currencyToBuy = $currencyToBuy;
        $this->currencyToSell = $currencyToSell;
    }

    public function getIdentityId(): string
    {
        return $this->identityId;
    }

    public function getMoneyToExchangeValue(): string
    {
        return $this->moneyToExchangeValue;
    }

    public function getMoneyToExchangeCurrency(): string
    {
        return $this->moneyToExchangeCurrency;
    }

    public function getCurrencyToBuy(): string
    {
        return $this->currencyToBuy;
    }

    public function getCurrencyToSell(): string
    {
        return $this->currencyToSell;
    }
}
