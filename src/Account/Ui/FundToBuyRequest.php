<?php

namespace App\Account\Ui;

class FundToBuyRequest
{
    private string $value;
    private string $currency;
    private string $rateCurrencyToBuy;
    private string $rateCurrencyToSell;
    private string $rateValue;

    public function __construct(string $value, string $currency, string $rateCurrencyToBuy, string $rateCurrencyToSell, string $rateValue)
    {
        $this->value = $value;
        $this->currency = $currency;
        $this->rateCurrencyToBuy = $rateCurrencyToBuy;
        $this->rateCurrencyToSell = $rateCurrencyToSell;
        $this->rateValue = $rateValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getRateCurrencyToBuy(): string
    {
        return $this->rateCurrencyToBuy;
    }

    public function setRateCurrencyToBuy(string $rateCurrencyToBuy): void
    {
        $this->rateCurrencyToBuy = $rateCurrencyToBuy;
    }

    public function getRateCurrencyToSell(): string
    {
        return $this->rateCurrencyToSell;
    }

    public function setRateCurrencyToSell(string $rateCurrencyToSell): void
    {
        $this->rateCurrencyToSell = $rateCurrencyToSell;
    }

    public function getRateValue(): string
    {
        return $this->rateValue;
    }

    public function setRateValue(string $rateValue): void
    {
        $this->rateValue = $rateValue;
    }
}