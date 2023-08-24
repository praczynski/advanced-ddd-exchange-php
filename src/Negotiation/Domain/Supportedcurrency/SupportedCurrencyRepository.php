<?php

namespace App\Negotiation\Domain\Supportedcurrency;

use App\Kernel\Currency;

interface SupportedCurrencyRepository
{
    function save(SupportedCurrency $supportedCurrency): void;

    function findByCurrency(Currency $baseCurrency, Currency $targetCurrency): ?SupportedCurrency;

    function findActiveByCurrency(Currency $baseCurrency, Currency $targetCurrency): ?SupportedCurrency;


}
