<?php

namespace App\Negotiation\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class OperatorId
{
    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $value): OperatorId
    {
        return new OperatorId(Uuid::fromString($value));
    }

    public static function generate(): OperatorId
    {
        return new OperatorId(Uuid::uuid4());
    }
}
