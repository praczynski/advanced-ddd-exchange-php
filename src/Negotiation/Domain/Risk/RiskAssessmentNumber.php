<?php

namespace App\Negotiation\Domain\Risk;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class RiskAssessmentNumber {

    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $uuid): RiskAssessmentNumber
    {
        return new RiskAssessmentNumber(Uuid::fromString($uuid));
    }

    public static function generate(): RiskAssessmentNumber
    {
        return new RiskAssessmentNumber(Uuid::uuid4());
    }
}