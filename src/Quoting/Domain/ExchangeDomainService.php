<?php

namespace App\Quoting\Domain;

use App\Kernel\Currency;
use App\Quoting\Domain\Policy\OneDayQuoteExpirationDatePolicy;
use App\Quoting\Domain\Policy\QuoteExpirationDatePolicy;

class ExchangeDomainService {

    public function getBestExchangeRate(Requester $requester, MoneyToExchange $moneyToExchange, iterable $advisors, Currency $currencyToSell, Currency $currencyToBuy): BestExchangeRate {

        $bestExchangeRate = null;

        foreach ($advisors as $advisor) {
            $exchangeRate = $advisor->exchangeRate($requester, $moneyToExchange, $currencyToSell, $currencyToBuy);

            if ($exchangeRate !== null) {
                if ($bestExchangeRate === null || $exchangeRate->isMoreFavorableThan($bestExchangeRate)) {
                    $bestExchangeRate = $exchangeRate;
                }
            }
        }

        if ($bestExchangeRate === null) {
            throw new \RuntimeException("No exchange rate available");
        }

        return new BestExchangeRate($bestExchangeRate->getCurrencyToSell(), $bestExchangeRate->getCurrencyToBuy(), $bestExchangeRate->getRate());
    }


    public function determineQuoteExpirationDatePolicy(Requester $requester): QuoteExpirationDatePolicy {
        return new OneDayQuoteExpirationDatePolicy();
    }
}
