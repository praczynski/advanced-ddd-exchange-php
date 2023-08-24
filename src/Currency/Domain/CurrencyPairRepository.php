<?php

namespace App\Currency\Domain;

use App\Kernel\Currency;

interface CurrencyPairRepository
{
    public function save(CurrencyPair $currencyPair): void;

    public function findById(CurrencyPairId $currencyPairId): ?CurrencyPair;

    public function alreadyExists(Currency $baseCurrency, Currency $targetCurrency): bool;

    public function findByBaseCurrencyAndTargetCurrency(Currency $baseCurrency, Currency $targetCurrency): ?CurrencyPair;

    public function findDataByBaseCurrencyAndTargetCurrency(Currency $baseCurrency, Currency $targetCurrency): ?CurrencyPairData;

    public function findAll(): array;
}
