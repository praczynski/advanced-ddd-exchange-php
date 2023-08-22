<?php

namespace App\Quoting\Domain;

use App\Kernel\Currency;
use App\Quoting\Domain\Policy\OneDayQuoteExpirationDatePolicy;
use App\Quoting\Domain\Policy\QuoteExpirationDatePolicy;

class ExchangeDomainService {

    public function getBestExchangeRate(Requester $requester, MoneyToExchange $moneyToExchange, iterable $advisors, Currency $currencyToSell, Currency $currencyToBuy): ?BestExchangeRate {
        return null;
    }


    public function determineQuoteExpirationDatePolicy(Requester $requester): QuoteExpirationDatePolicy {
        return new OneDayQuoteExpirationDatePolicy();
    }
}
