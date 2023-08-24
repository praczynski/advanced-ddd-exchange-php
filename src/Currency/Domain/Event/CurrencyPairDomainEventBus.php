<?php

namespace App\Currency\Domain\Event;

interface CurrencyPairDomainEventBus
{
    public function postCurrencyPairCreated(CurrencyPairCreated $currencyPairCreated): void;
    public function postCurrencyPairExchangeRateAdjusted(CurrencyPairExchangeRateAdjusted $currencyPairExchangeRateAdjusted): void;
    public function postCurrencyPairDeactivated(CurrencyPairDeactivated $currencyPairDeactivated): void;
    public function postCurrencyPairActivated(CurrencyPairActivated $currencyPairActivated): void;
}
