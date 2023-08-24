<?php

namespace App\Negotiation\Domain\Risk;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use http\Exception\InvalidArgumentException;

#[Embeddable]
class RiskLevel {

    private const LOW = "LOW";
    private const MEDIUM = "MEDIUM";
    private const HIGH = "HIGH";

    #[Column(type: "string")]
    private string $level;

    public function __construct(string $level) {
        if (!in_array($level, [self::LOW, self::MEDIUM, self::HIGH], true)) {
            throw new InvalidArgumentException("Invalid risk level");
        }
        $this->level = $level;
    }

    public static function LOW(): self {
        return new self(self::LOW);
    }

    public static function MEDIUM(): self {
        return new self(self::MEDIUM);
    }

    public static function HIGH(): self {
        return new self(self::HIGH);
    }
}
