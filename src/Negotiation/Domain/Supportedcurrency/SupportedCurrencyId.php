<?php

namespace App\Negotiation\Domain\Supportedcurrency;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class SupportedCurrencyId
{
    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): SupportedCurrencyId
    {
        return new SupportedCurrencyId(Uuid::uuid4());
    }
}
