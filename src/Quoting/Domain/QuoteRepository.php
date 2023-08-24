<?php

namespace App\Quoting\Domain;

use App\Kernel\Currency;
use App\Kernel\Money;
use Ramsey\Uuid\UuidInterface;

interface QuoteRepository
{

    public function save(Quote $quote): void;
    public function findActiveQuote(Requester $requester, Currency $currencyToSell, Currency $currencyToBuy, MoneyToExchange $moneyToExchange): ?Quote;
    public function findActiveQuoteByNumber(QuoteNumber $quoteNumber): ?Quote;
    public function getQuote(QuoteNumber $quoteNumber): Quote;
    public function findAllQuotesToExpire(): array;
    public function findAllQuotesToExpireByCurrency(Currency $currencyToSell, Currency $currencyToBuy): array;
}
