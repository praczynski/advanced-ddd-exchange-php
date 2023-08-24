<?php

namespace App\Quoting\Domain;

use App\Kernel\Currency;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use Exception;

#[Embeddable]
class BestExchangeRate {
    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $currencyToSell;
    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $currencyToBuy;
    #[Embedded(class: "App\Quoting\Domain\Rate")]
    private Rate $rate;


    public function __construct(Currency $currencyToSell, Currency $currencyToBuy, Rate $rate) {
        if($currencyToSell->equals($currencyToBuy)) {
            throw new Exception("Currencies are the same");
        }
        $this->currencyToSell = $currencyToSell;
        $this->currencyToBuy = $currencyToBuy;
        $this->rate = $rate;
    }

    public function isMoreFavorableThan(BestExchangeRate $rate): bool {
        if (!$this->currencyToBuy->equals($rate->currencyToBuy)) {
            throw new Exception("Different currencies");
        }
        if (!$this->currencyToSell->equals($rate->currencyToSell)) {
            throw new Exception("Different currencies");
        }
        return $this->rate->compareTo($rate->rate) > 0;
    }

    public function applyDiscount(DiscountLevel $discountLevel): BestExchangeRate {
        return new BestExchangeRate(
            $this->currencyToSell,
            $this->currencyToBuy,
            $discountLevel->calculate($this->rate)
        );
    }

    public function exchange(MoneyToExchange $moneyToExchange): MoneyExchanged {
        if ($moneyToExchange->theSameCurrency($this->currencyToSell)) {
            return $moneyToExchange->multiplyWithChangeCurrency($this->rate, $this->currencyToBuy);
        }
        return $moneyToExchange->divWithChangeCurrency($this->rate, $this->currencyToSell);
    }

    public function equals(ExchangeRate $rate): bool {
        return $rate->equalsRate($this->rate);
    }
}
