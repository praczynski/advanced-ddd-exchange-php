<?php

namespace App\Account\Domain;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class TransactionNumber {

    #[Column(name: "uuid", type: "uuid")]
    private UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): TransactionNumber
    {
        return new TransactionNumber(Uuid::uuid4());
    }
}
