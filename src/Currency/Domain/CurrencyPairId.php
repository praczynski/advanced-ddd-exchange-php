<?php

namespace App\Currency\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class CurrencyPairId
{
    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $value): CurrencyPairId
    {
        return new CurrencyPairId(Uuid::fromString($value));
    }

    public static function generate(): CurrencyPairId
    {
        return new CurrencyPairId(Uuid::uuid4());
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }


}
