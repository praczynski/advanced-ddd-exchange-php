<?php

namespace App\Negotiation\Domain;

use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class Operator
{
    #[Embedded(class: "OperatorId")]
    private OperatorId $operatorId;

    public function __construct(OperatorId $operatorId)
    {
        $this->operatorId = $operatorId;
    }
}

