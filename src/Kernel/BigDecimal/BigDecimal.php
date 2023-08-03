<?php

namespace App\Kernel\BigDecimal;

use Brick\Math\Exception\MathException;
use Brick\Math\BigDecimal as BrickBigDecimal;


class BigDecimal
{
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
}