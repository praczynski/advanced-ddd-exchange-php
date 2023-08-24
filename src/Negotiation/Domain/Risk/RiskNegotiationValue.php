<?php

namespace App\Negotiation\Domain\Risk;


use App\Kernel\Money;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use InvalidArgumentException;

#[Embeddable]
class RiskNegotiationValue {

    #[Embedded(class: "App\Kernel\Money", columnPrefix: false)]
    private Money $value;

     public function __construct(Money $value) {
        if($value->isNegative()) {
            throw new InvalidArgumentException("Value cannot be null");
        }
        $this->value = $value;
    }

    public static function fromMoney(Money $value): RiskNegotiationValue {
        return new RiskNegotiationValue($value);
    }

    public function add(RiskNegotiationValue $riskNegotiationValue): RiskNegotiationValue {
        return new RiskNegotiationValue($this->value->add($riskNegotiationValue->value));
    }

}
