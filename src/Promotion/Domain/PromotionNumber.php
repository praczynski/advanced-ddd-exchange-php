<?php

namespace App\Promotion\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class PromotionNumber {

    #[Column(type: "uuid", unique: true)]
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid) {
        $this->uuid = $uuid;
    }

    public static function generate(): PromotionNumber {
        return new PromotionNumber(Uuid::uuid4());
    }

    public static function fromString(string $uuid): PromotionNumber {
        return new PromotionNumber(Uuid::fromString($uuid));
    }

    public function equals(PromotionNumber $promotionNumber): bool {
        return $this->uuid->equals($promotionNumber->uuid);
    }

    public function toString(): string {
        return $this->uuid->toString();
    }

    public function __toString(): string {
        return $this->toString();
    }
}
