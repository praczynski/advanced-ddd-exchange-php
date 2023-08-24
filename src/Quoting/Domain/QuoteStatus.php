<?php

namespace App\Quoting\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class QuoteStatus
{
    private const EXPIRED_VALUE = "EXPIRED";
    private const ACCEPTED_VALUE = "ACCEPTED";
    private const REJECTED_VALUE = "REJECTED";
    private const PREPARED_VALUE = "PREPARED";

    #[Column(type: "string", length: 50)]
    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function EXPIRED(): self
    {
        return new self(self::EXPIRED_VALUE);
    }

    public static function ACCEPTED(): self
    {
        return new self(self::ACCEPTED_VALUE);
    }

    public static function REJECTED(): self
    {
        return new self(self::REJECTED_VALUE);
    }

    public static function PREPARED(): self
    {
        return new self(self::PREPARED_VALUE);
    }

    public function toString(): string
    {
        return $this->status;
    }
}
