<?php

namespace App\Negotiation\Infrastructure;


use App\Currency\Application\CurrencyPairApplicationService;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Negotiation\Application\BaseExchangeRateAdvisor;

class APPBaseExchangeRateAdvisor implements BaseExchangeRateAdvisor
{
    private CurrencyPairApplicationService $currencyPairApplicationService;

    public function __construct(CurrencyPairApplicationService $currencyPairApplicationService)
    {
        $this->currencyPairApplicationService = $currencyPairApplicationService;
    }

    public function baseExchangeRate(Currency $baseCurrency, Currency $targetCurrency): ?BigDecimal
    {
        $currencyPair = $this->currencyPairApplicationService->getCurrencyPair($baseCurrency, $targetCurrency);

        if ($currencyPair->getStatus() === "FAILURE") {
            return null;
        }

        return BigDecimal::fromString($currencyPair->getExchangeRate());
    }
}