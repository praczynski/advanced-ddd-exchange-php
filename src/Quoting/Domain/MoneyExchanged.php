<?php

namespace App\Quoting\Domain;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\Money;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use RuntimeException;

#[Embeddable]
class MoneyExchanged {

    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $value;
    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $currency;

    public function __construct(BigDecimal $value, Currency $currency) {
        if($value->isNegativeOrZero()) {
            throw new RuntimeException("Value cannot be negative");
        }
        $this->value = $value;
        $this->currency = $currency;
    }

    public static function create(BigDecimal $value, Currency $currency): self {
        return new self($value, $currency);
    }

    public function theSameCurrency(Currency $currency): bool {
        return $this->currency->equals($currency);
    }

    public function multiplyWithChangeCurrency(Rate $rate, Currency $currency): self {
        return new self($rate->multiplyToBigDecimal($this->value), $currency);
    }

    public function divWithChangeCurrency(Rate $rate, Currency $currency): self {
        return new self($rate->divToBigDecimal($this->value), $currency);
    }

    public function toMoney(): Money {
        return new Money($this->value, $this->currency);
    }

    public function equals(Money $money): bool {
        return $money->equals(new Money($this->value, $this->currency));
    }

   public function toString(): string {
        return $this->value->toString() . " " . $this->currency->toString();
    }


}
