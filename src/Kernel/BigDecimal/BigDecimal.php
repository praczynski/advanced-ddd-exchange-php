<?php

namespace App\Kernel\BigDecimal;

use Brick\Math\Exception\MathException;
use Brick\Math\BigDecimal as BrickBigDecimal;
use Brick\Math\RoundingMode;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;

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
                throw new InvalidArgumentException('Value cannot have more than 2 decimal places');
            }
        } catch (MathException $e) {
            throw new InvalidArgumentException('Invalid value');
        }
    }

    public static function fromString(string $value): BigDecimal
    {
        return new BigDecimal($value);
    }

    public static function fromBrickBigDecimal(BrickBigDecimal $value): BigDecimal
    {
       return new BigDecimal($value->jsonSerialize());
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

    public function subtract(BigDecimal $value): BigDecimal
    {
        try {
            return new BigDecimal($this->value->minus($value->value)->jsonSerialize());
        } catch (MathException $e) {
            throw new \InvalidArgumentException('Invalid value');
        }
    }

    public function divide(BigDecimal $divisor, int $scale = 2, int $roundingMode = RoundingMode::HALF_UP): BigDecimal
    {
        try {
            $result = $this->value->dividedBy($divisor->value, $scale, $roundingMode);
            return new BigDecimal($result->jsonSerialize());
        } catch (MathException $e) {
            throw new \InvalidArgumentException('Invalid value');
        }
    }

    public function isNegativeOrZero(): bool
    {
        return $this->value->isNegativeOrZero();
    }

    /**
     * It takes a callable function (converter) as an argument and applies it to the IdentityId instance.
     *
     * @param callable $converter
     * @return mixed
     */
    public function identity(callable $converter): mixed
    {
        return $converter($this->value);
    }

    public function equals(BigDecimal $other): bool
    {
        return $this->value->compareTo($other->value) === 0;
    }
}