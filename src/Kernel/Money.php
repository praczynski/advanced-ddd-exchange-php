<?php

namespace App\Kernel;


use App\Kernel\BigDecimal\BigDecimal;

class Money
{
    private BigDecimal $value;
    private Currency $currency;

    public function __construct(BigDecimal $value, Currency $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    public function add(Money $money): Money
    {
        if (!$this->currency->equals($money->currency)) {
            throw new \InvalidArgumentException('Currencies must be the same');
        }

        return new Money($this->value->add($money->value), $this->currency);
    }

    public function toString(): string
    {
        return $this->value->toString() . ' ' . $this->currency->toString();
    }
}