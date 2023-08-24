<?php

namespace App\PromotionSaga;

use App\Kernel\IdentityId;

class AccountActivatedForPromotion {
    private string $identityId;

    public function __construct(string $identityId) {
        $this->identityId = $identityId;
    }

    public function getIdentityId(): string {
        return $this->identityId;
    }
}
