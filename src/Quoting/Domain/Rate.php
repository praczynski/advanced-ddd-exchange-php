<?php

namespace App\Quoting\Domain;

use App\Kernel\BigDecimal\BigDecimal;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use RuntimeException;
use Brick\Math\RoundingMode;

#[Embeddable]
class Rate {

    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $value;

    private function __construct(BigDecimal $value) {
        if ($value->compareTo(BigDecimal::fromString("0")) < 0) {
            throw new RuntimeException("Rate cannot be null or negative");
        }
        $this->value = $value;
    }

    public static function fromBigDecimal(BigDecimal $value): self {
        return new self($value);
    }

    public static function fromString(string $value): self {
        return new self(BigDecimal::fromString($value));
    }

    public function multiply(BigDecimal $value): Rate {
        return new Rate($this->value->multiply($value)->divide(BigDecimal::fromString("1"), 2, RoundingMode::HALF_UP));
    }

    public function multiplyToBigDecimal(BigDecimal $value): BigDecimal {
        return $this->value->multiply($value)->divide(BigDecimal::fromString("1"), 2, RoundingMode::HALF_UP);
    }

    public function divToBigDecimal(BigDecimal $value): BigDecimal {
        return $value->divide($this->value, 2, RoundingMode::HALF_UP)->divide(BigDecimal::fromString("1"), 2, RoundingMode::HALF_UP);
    }

    public function compareTo(Rate $rate): int {
        return $this->value->compareTo($rate->value);
    }

    public function equals(Rate $rate): bool {
        return $this->value->compareTo($rate->value) === 0;
    }
}
