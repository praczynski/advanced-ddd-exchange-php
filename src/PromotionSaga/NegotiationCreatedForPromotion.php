<?php

namespace App\PromotionSaga;

use App\Kernel\IdentityId;

class NegotiationCreatedForPromotion {
    private string $uuid;

    public function __construct(string $uuid) {
        $this->uuid = $uuid;
    }

    public function getUuid(): string {
        return $this->uuid;
    }
}
