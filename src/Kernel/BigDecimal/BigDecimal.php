<?php

namespace App\Kernel\BigDecimal;

use Brick\Math\Exception\MathException;
use Brick\Math\BigDecimal as BrickBigDecimal;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class BigDecimal
{
    #[Column(type: "big_decimal")]
    private BrickBigDecimal $value;

    public function __construct(string $value)
    {
        try {
            $this->value = BrickBigDecimal::of($value);
            if ($this->value->getScale() > 2) {
                throw new \InvalidArgumentException('Value cannot have more than 2 decimal places');
            }
        } catch (MathException $e) {
            throw new \InvalidArgumentException('Invalid value');
        }
    }

    public static function fromString(string $value): BigDecimal
    {
        return new BigDecimal($value);
    }

    public function add(BigDecimal $value): BigDecimal
    {
        try {
            return new BigDecimal($this->value->plus($value->value)->jsonSerialize());
        } catch (MathException $e) {
            throw new \InvalidArgumentException('Invalid value');
        }
    }

    public function toString(): string
    {
        return $this->value->jsonSerialize();
    }

    public function getScale(): int
    {
        return $this->value->getScale();
    }

    /**
     * @throws MathException
     */
    public function compareTo(BigDecimal $value): int
    {
        return $this->value->compareTo($value->value);
    }

    public function multiply(BigDecimal $rate):BigDecimal
    {
       return new BigDecimal($this->value->multipliedBy($rate->value));

    }

    public function subtract(BigDecimal $value)
    {
        try {
            return new BigDecimal($this->value->minus($value->value)->jsonSerialize());
        } catch (MathException $e) {
            throw new \InvalidArgumentException('Invalid value');
        }
    }
}