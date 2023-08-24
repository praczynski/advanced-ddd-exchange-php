<?php

namespace App\Negotiation\Domain\Supportedcurrency;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Status
{
    private const ACTIVE = 'ACTIVE';
    private const INACTIVE = 'INACTIVE';

    #[Column(name: "status", type: "string")]
    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public static function ACTIVE(): self
    {
        return new self(self::ACTIVE);
    }

    public static function INACTIVE(): self
    {
        return new self(self::INACTIVE);
    }

    public function toString(): string
    {
        return $this->status;
    }
}