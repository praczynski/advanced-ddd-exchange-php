<?php

namespace App\Negotiation\Domain;

use App\Kernel\BigDecimal\BigDecimal;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class NegotiationRate
{
    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $proposedRate;

    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $baseExchangeRate;

    #[Embedded(class: "App\Kernel\BigDecimal\BigDecimal")]
    private BigDecimal $differenceInPercentage;

    public function __construct(BigDecimal $proposedRate, BigDecimal $baseExchangeRate)
    {
        $this->proposedRate = $proposedRate;
        $this->baseExchangeRate = $baseExchangeRate;
        $this->differenceInPercentage = $this->calculateDifferenceInPercentage();
    }

    private function calculateDifferenceInPercentage(): BigDecimal
    {
        $subtract = $this->baseExchangeRate->subtract($this->proposedRate);
        return $subtract->divide($this->baseExchangeRate)->multiply(new BigDecimal("100"));
    }

    public function differenceInPercentage(): BigDecimal
    {
        return $this->differenceInPercentage;
    }
}
