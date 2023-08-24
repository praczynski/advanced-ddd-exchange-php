<?php

namespace App\Negotiation\Domain\Supportedcurrency;

use App\Kernel\BigDecimal\BigDecimal;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use http\Exception\RuntimeException;

#[Embeddable]
class Rate {

    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $value;

    public function __construct(BigDecimal $value) {
        if ($value->isNegativeOrZero()) {
            throw new RuntimeException("Rate cannot be null or negative");
        }
        $this->value = $value;
    }

    public function value($converter) {
        return $converter($this->value);
    }

}
