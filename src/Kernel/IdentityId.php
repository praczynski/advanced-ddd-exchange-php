<?php

namespace App\Kernel;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class IdentityId
{
    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): IdentityId
    {
        return new IdentityId(Uuid::uuid4());
    }

    public static function fromString(string $uuid): IdentityId
    {
        return new IdentityId(Uuid::fromString($uuid));
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @return UuidInterface
     */
    function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @param UuidInterface $uuid
     */
    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }
}
