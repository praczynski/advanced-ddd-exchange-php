<?php

namespace App\Negotiation\Domain\Risk;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class RiskLineId {

    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid) {
        $this->uuid = $uuid;
    }

    public static function generate(): RiskLineId {
        return new RiskLineId(Uuid::uuid4());
    }
}