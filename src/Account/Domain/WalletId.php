<?php

namespace App\Account\Domain;


use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class WalletId {

    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): WalletId
    {
        return new WalletId(Uuid::uuid4());
    }

    public function equals(WalletId $walletId): bool {
        return $this->uuid->equals($walletId->uuid);
    }

    public static function fromString(string $uuid): WalletId
    {
        return new WalletId(Uuid::fromString($uuid));
    }

    public function __toString(): string
    {
        return $this->uuid->toString();
    }
}
