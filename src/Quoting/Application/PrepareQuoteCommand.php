<?php

namespace App\Quoting\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;

class PrepareQuoteCommand {

    private IdentityId $identityId;
    private BigDecimal $moneyToExchangeValue;
    private Currency $moneyToExchangeCurrency;
    private Currency $currencyToSell;
    private Currency $currencyToBuy;

    public function __construct(
        IdentityId $identityId,
        BigDecimal $moneyToExchangeValue,
        Currency $moneyToExchangeCurrency,
        Currency $currencyToSell,
        Currency $currencyToBuy
    ) {
        $this->identityId = $identityId;
        $this->moneyToExchangeValue = $moneyToExchangeValue;
        $this->moneyToExchangeCurrency = $moneyToExchangeCurrency;
        $this->currencyToSell = $currencyToSell;
        $this->currencyToBuy = $currencyToBuy;
    }

    public function getIdentityId(): IdentityId {
        return $this->identityId;
    }

    public function getMoneyToExchangeValue(): BigDecimal {
        return $this->moneyToExchangeValue;
    }

    public function getMoneyToExchangeCurrency(): Currency {
        return $this->moneyToExchangeCurrency;
    }

    public function getCurrencyToSell(): Currency {
        return $this->currencyToSell;
    }

    public function getCurrencyToBuy(): Currency {
        return $this->currencyToBuy;
    }
}
