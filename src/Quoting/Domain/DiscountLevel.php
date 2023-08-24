<?php

namespace App\Quoting\Domain;

use App\Kernel\BigDecimal\BigDecimal;

class DiscountLevel {

    public const FIVE_PERCENT = 0.95;

    private BigDecimal $discount;

    private function __construct(BigDecimal $discount) {
        $this->discount = $discount;
    }

    public function calculate(Rate $value): Rate {
        return $value->multiply($this->discount);
    }
}
