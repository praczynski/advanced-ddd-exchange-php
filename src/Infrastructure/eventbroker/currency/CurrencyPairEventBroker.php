<?php

namespace App\Infrastructure\eventbroker\currency;

use App\Currency\Domain\Event\CurrencyPairActivated;
use App\Currency\Domain\Event\CurrencyPairCreated;
use App\Currency\Domain\Event\CurrencyPairDeactivated;
use App\Currency\Domain\Event\CurrencyPairDomainEventBus;
use App\Currency\Domain\Event\CurrencyPairExchangeRateAdjusted;
use App\Negotiation\Application\SupportedCurrencyApplicationService;

class CurrencyPairEventBroker implements CurrencyPairDomainEventBus
{
    private SupportedCurrencyApplicationService $supportedCurrencyApplicationService;

    public function __construct(SupportedCurrencyApplicationService $supportedCurrencyApplicationService)
    {
        $this->supportedCurrencyApplicationService = $supportedCurrencyApplicationService;
    }

    public function postCurrencyPairCreated(CurrencyPairCreated $currencyPairCreated): void
    {
        $this->supportedCurrencyApplicationService->addSupportedCurrency(
            $currencyPairCreated->getBaseCurrency(),
            $currencyPairCreated->getTargetCurrency(),
            $currencyPairCreated->getRate()
        );
    }

    public function postCurrencyPairExchangeRateAdjusted(CurrencyPairExchangeRateAdjusted $currencyPairExchangeRateAdjusted): void
    {
        $this->supportedCurrencyApplicationService->adjustCurrencyPair(
            $currencyPairExchangeRateAdjusted->getBaseCurrency(),
            $currencyPairExchangeRateAdjusted->getTargetCurrency(),
            $currencyPairExchangeRateAdjusted->getAdjustedRate()
        );
    }

    public function postCurrencyPairDeactivated(CurrencyPairDeactivated $currencyPairDeactivated): void
    {
        $this->supportedCurrencyApplicationService->deactivateCurrencyPair(
            $currencyPairDeactivated->getBaseCurrency(),
            $currencyPairDeactivated->getTargetCurrency()
        );
    }

    public function postCurrencyPairActivated(CurrencyPairActivated $currencyPairActivated): void
    {
        $this->supportedCurrencyApplicationService->activateCurrencyPair(
            $currencyPairActivated->getBaseCurrency(),
            $currencyPairActivated->getTargetCurrency()
        );
    }
}