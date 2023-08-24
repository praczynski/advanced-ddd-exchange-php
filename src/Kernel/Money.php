<?php

namespace App\Kernel;


use App\Kernel\BigDecimal\BigDecimal;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use InvalidArgumentException;

#[Embeddable]
class Money
{
    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal", columnPrefix: false)]
    private BigDecimal $value;

    #[Embedded(class: "App\Kernel\Currency", columnPrefix: false)]
    private Currency $currency;

    public function __construct(BigDecimal $value, Currency $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    public function add(Money $money): Money
    {
        if (!$this->currency->equals($money->currency)) {
            throw new InvalidArgumentException('Currencies must be the same');
        }

        return new Money($this->value->add($money->value), $this->currency);
    }

    public function sub(Money $money): Money
    {
        if (!$this->currency->equals($money->currency)) {
            throw new InvalidArgumentException('Currencies must be the same');
        }

        return new Money($this->value->subtract($money->value), $this->currency);
    }

    public function theSameMoneyCurrency(Money $money): bool {
        return $this->currency->equals($money->currency);
    }
    public function theSameCurrency(Currency $currency): bool {
        return $this->currency->equals($currency);
    }

    public function toString(): string
    {
        return $this->value->toString() . ' ' . $this->currency->toString();
    }

    public function lessThan(Money $valueToCompare): bool {
        return $this->value->compareTo($valueToCompare->value) < 0;
    }

    public function isNegative(): bool {
        return $this->value->compareTo(new BigDecimal("0")) < 0;
    }
    public function multiply(BigDecimal $rate): BigDecimal {
        return $this->value->multiply($rate);
    }

    public function compareTo(Money $money): int {
        return $this->value->compareTo($money->value);
    }

    public function equals(Money $money): bool
    {
        if ($this === $money) {
            return true;
        }

        return $this->value->equals($money->value) && $this->currency->equals($money->currency);
    }
}