<?php

namespace App\Currency\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Status
{
    #[Column(type: "string")]
    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function ACTIVE(): Status
    {
        return new Status("ACTIVE");
    }

    public static function INACTIVE(): Status
    {
        return new Status("INACTIVE");
    }

    public function toString(): string
    {
        return $this->status;
    }
}

