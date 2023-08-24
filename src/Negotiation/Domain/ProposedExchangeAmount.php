<?php

namespace App\Negotiation\Domain;


use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\Money;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use InvalidArgumentException;

#[Embeddable]
class ProposedExchangeAmount
{
    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $value;

    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $currency;

    private function __construct(BigDecimal $value, Currency $currency)
    {
        if ($value->compareTo(new BigDecimal("0")) < 0) {
            throw new InvalidArgumentException("Value cannot be negative");
        }
        $this->value = $value;
        $this->currency = $currency;
    }

    public static function ZERO_PLN(): ProposedExchangeAmount
    {
        return new ProposedExchangeAmount(new BigDecimal("0"), Currency::PLN());
    }

    public static function fromValueAndCurrency(BigDecimal $value, Currency $currency): ProposedExchangeAmount
    {
        return new ProposedExchangeAmount($value, $currency);
    }

    public function isMoreOrEquals(ProposedExchangeAmount $money): bool
    {
        return $this->value->compareTo($money->value) >= 0;
    }

    public function theSameCurrency(Currency $currency): bool
    {
        return $this->currency->equals($currency);
    }

    public function asMoney(): Money
    {
        return new Money($this->value, $this->currency);
    }
}