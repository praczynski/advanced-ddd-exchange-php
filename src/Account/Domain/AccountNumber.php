<?php

namespace App\Account\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AccountNumber {

    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): AccountNumber
    {
        return new AccountNumber(Uuid::uuid4());
    }

    public static function fromString(string $uuid): AccountNumber
    {
        return new AccountNumber(Uuid::fromString($uuid));
    }

    public function equals(AccountNumber $accountNumber): bool {
        return $this->uuid->equals($accountNumber->uuid);
    }

    public function toString(): string {
        return $this->uuid->toString();
    }

    public function __toString(): string {
        return $this->toString();
    }

}
