<?php

namespace App\Negotiation\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class NegotiationId
{
    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $value): NegotiationId
    {
        return new NegotiationId(Uuid::fromString($value));
    }
    public static function generate(): NegotiationId
    {
        return new NegotiationId(Uuid::uuid4());
    }
}