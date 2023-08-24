<?php

namespace App\Account\Domain;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Status {

    private const ACTIVE = "ACTIVE";
    private const INACTIVE = "INACTIVE";

    #[Column(name: "value", type: "string")]
    private string $value;

    private function __construct(string $status) {
        $this->value = $status;
    }

    public static function createActive(): self {
        return new self(self::ACTIVE);
    }

    public static function createInactive(): self {
        return new self(self::INACTIVE);
    }
}
