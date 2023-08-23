<?php

namespace App\Account\Domain;

use App\Account\Domain\Exception\DifferentCurrenciesException;
use App\Account\Domain\Exception\InsufficientFundsException;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\Money;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class Funds {

    #[Embedded(class: "App\Kernel\Money", columnPrefix: false)]
    private Money $value;

    private function __construct(Money $money)
    {
        if($money->isNegative()){
            throw new \InvalidArgumentException("Value cannot be negative");
        }
        $this->value = $money;
    }

    public static function fromValueAndCurrency(BigDecimal $value, Currency $currency): self
    {
        if ($value->getScale() > 2 || $value->compareTo(new BigDecimal("0")) < 0) {
            throw new \InvalidArgumentException("Value cannot have more than 2 decimal places");
        }

        return new Funds(new Money($value, $currency));
    }

    public static function zeroPLN():Funds
    {
        return new Funds(new Money(new BigDecimal("0"), new Currency("PLN")));
    }

    public static function fromMoney(Money $value): self
    {
        return new Funds($value);
    }

    /**
     * @throws \Exception
     */
    public function addFunds(Funds $funds): self {

        if(!$this->value->theSameMoneyCurrency($funds->value)){
            throw new \RuntimeException("Currencies must be the same");
        }

        return new Funds($this->value->add($funds->value));
    }


    public function withdrawFunds(Funds $funds): Funds {
        if (!$this->value->theSameMoneyCurrency($funds->value)) {
            throw new DifferentCurrenciesException();
        }
        if ($this->value->lessThan($funds->value)) {
            throw new InsufficientFundsException();
        }
        return new Funds($this->value->sub($funds->value));
    }

    public function sumFunds(Funds $funds, Funds $funds2): Funds {
        if (!$funds->value->theSameMoneyCurrency($funds2->value)) {
            throw new DifferentCurrenciesException();
        }
        return new Funds($funds->value->add($funds2->value));
    }

    public function equals(Funds $funds): bool {
        return $this->value->equals($funds->value);
    }

    public function lessOrEqualsThan(Funds $valueToCompare): bool {
        return $this->value->compareTo($valueToCompare->value) <= 0;
    }

    public function greaterOrEqualsThan(Funds $valueToCompare): bool {
        return $this->value->compareTo($valueToCompare->value) >= 0;
    }

    public function isSameCurrency(Currency $currency): bool {
        return $this->value->theSameCurrency($currency);
    }

    public function isSameCurrencyFunds(Funds $funds): bool {
        return $this->value->theSameMoneyCurrency($funds->value);
    }

    public function __toString(): string {
        return $this->value->toString();
    }

    public function multiply(BigDecimal $rate, Currency $currency): Funds {
        $multipliedValue = $this->value->multiply($rate);
        return new Funds(new Money($multipliedValue, $currency));
    }

}
