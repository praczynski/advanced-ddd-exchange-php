<?php

namespace App\Quoting\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class QuoteNumber
{
    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $value): QuoteNumber
    {
        return new QuoteNumber(Uuid::fromString($value));
    }

    public static function generate(): QuoteNumber
    {
        return new QuoteNumber(Uuid::uuid4());
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
