<?php

namespace App\Promotion\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class PromotionType
{
    #[Column(type: "string")]
    private string $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function NEW_TRADER(): PromotionType
    {
        return new PromotionType("NEW_TRADER");
    }

    public static function LOYAL_CUSTOMER(): PromotionType
    {
        return new PromotionType("LOYAL_CUSTOMER");
    }

    public static function VIP_CUSTOMER(): PromotionType
    {
        return new PromotionType("VIP_CUSTOMER");
    }

    public function isLoyalCustomer(): bool
    {
        return $this->type === self::LOYAL_CUSTOMER()->type;
    }

    public function isVIPCustomer(): bool
    {
        return $this->type === self::VIP_CUSTOMER()->type;
    }

    public function isNewTrader(): bool
    {
        return $this->type === self::NEW_TRADER()->type;
    }

    public function toString(): string
    {
        return $this->type;
    }
}
