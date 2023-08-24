<?php

namespace App\Promotion\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Embedded;

#[Entity]
#[Table(name: "promotions")]
class Promotion {

    //cannot create primary key for embedded object

    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: PromotionNumber::class)]
    private PromotionNumber $promotionNumber;

    #[Embedded(class: TargetCustomer::class)]
    private TargetCustomer $targetCustomer;

    #[Embedded(class: PromotionType::class, columnPrefix: false)]
    private PromotionType $promotionType;

    public function __construct(TargetCustomer $targetCustomer, PromotionType $promotionType) {
        $this->promotionNumber = PromotionNumber::generate();
        $this->targetCustomer = $targetCustomer;
        $this->promotionType = $promotionType;
    }
}
