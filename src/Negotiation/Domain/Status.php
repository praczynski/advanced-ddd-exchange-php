<?php

namespace App\Negotiation\Domain;

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

    public static function PENDING(): Status
    {
        return new Status("PENDING");
    }

    public static function APPROVED(): Status
    {
        return new Status("APPROVED");
    }

    public static function REJECTED_TOO_SMALL_AMOUNT(): Status
    {
        return new Status("REJECTED_TOO_SMALL_AMOUNT");
    }

    public static function REJECTED(): Status
    {
        return new Status("REJECTED");
    }

    public static function EXPIRED(): Status
    {
        return new Status("EXPIRED");
    }

    public function isApproved(): bool
    {
        return $this->status === self::APPROVED()->status;
    }

    public function toString(): string
    {
        return $this->status;
    }
}
