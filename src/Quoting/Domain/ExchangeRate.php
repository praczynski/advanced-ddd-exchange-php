<?php

namespace App\Quoting\Domain;

use App\Kernel\Currency;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use RuntimeException;

#[Embeddable]
class ExchangeRate {
    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $currencyToSell;

    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $currencyToBuy;

    #[Embedded(class: "App\Quoting\Domain\Rate")]
    private Rate $rate;

    private function __construct(Currency $currencyToSell, Currency $currencyToBuy, Rate $rate) {
        if ($currencyToSell->equals($currencyToBuy)) {
            throw new RuntimeException("Currencies are the same");
        }
        $this->currencyToSell = $currencyToSell;
        $this->currencyToBuy = $currencyToBuy;
        $this->rate = $rate;
    }

    public static function create(Currency $currencyToSell, Currency $currencyToBuy, Rate $rate): self {
        return new self($currencyToSell, $currencyToBuy, $rate);
    }

    public function isMoreFavorableThan(ExchangeRate $rate): bool {
        if (!$this->currencyToBuy->equals($rate->currencyToBuy)) {
            throw new RuntimeException("Different currencies");
        }
        if (!$this->currencyToSell->equals($rate->currencyToSell)) {
            throw new RuntimeException("Different currencies");
        }
        return $this->rate->compareTo($rate->rate) > 0;
    }

    public function applyDiscount(DiscountLevel $discountLevel): self {
        return new self(
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

    public function getCurrencyToSell(): Currency {
        return $this->currencyToSell;
    }

    public function getCurrencyToBuy(): Currency {
        return $this->currencyToBuy;
    }

    public function getRate(): Rate {
        return $this->rate;
    }

    public function equalsRate(Rate $rate): bool {
        if ($this->rate == $rate) return true;
        return $this->rate->equals($rate);
    }
}
