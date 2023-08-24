<?php

namespace App\Currency\Domain;

use App\Kernel\BigDecimal\BigDecimal;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class ExchangeRate
{
    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $baseRate;
    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $adjustedRate;

    private function __construct(BigDecimal $baseRate, BigDecimal $adjustedRate = null)
    {
        $this->baseRate = $baseRate;
        $this->adjustedRate = $adjustedRate ?? $baseRate;
    }

    public static function withBaseRate(BigDecimal $baseRate): ExchangeRate
    {
        return new ExchangeRate($baseRate);
    }

    public function adjust($adjustedRate): ExchangeRate
    {
        return new ExchangeRate($this->baseRate, $adjustedRate);
    }

    public function getBaseRate(): BigDecimal
    {
        return $this->baseRate;
    }

    public function getAdjustedRate(): BigDecimal
    {
        return $this->adjustedRate;
    }
    public function baseRate(callable $converter) {
        return $converter($this->baseRate);
    }
}

