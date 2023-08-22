<?php

namespace App\Quoting\Infrastructure;

use App\Currency\Application\CurrencyPairApplicationService;
use App\Quoting\Domain\ExchangeRate;
use App\Quoting\Domain\ExchangeRateAdvisor;
use App\Quoting\Domain\Rate;


class APPBaseCurrencyExchangeRateAdvisor implements ExchangeRateAdvisor
{
    private CurrencyPairApplicationService $currencyPairApplicationService;

    public function __construct(CurrencyPairApplicationService $currencyPairApplicationService)
    {
        $this->currencyPairApplicationService = $currencyPairApplicationService;
    }

    public function exchangeRate($requester, $moneyToExchange, $currencyToSell, $currencyToBuy): ?ExchangeRate
    {
        $currencyPair = $this->currencyPairApplicationService->getCurrencyPair($currencyToSell, $currencyToBuy);

        if ($currencyPair->getStatus() === "FAILURE") {
            return null;
        }

        if ($currencyPair->getAdjustedExchangeRate() === null) {
            return ExchangeRate::create($currencyToSell, $currencyToBuy, Rate::fromString($currencyPair->getExchangeRate()));
        } else {
            return ExchangeRate::create($currencyToSell, $currencyToBuy, Rate::fromString($currencyPair->getAdjustedExchangeRate()));
        }
    }
}
