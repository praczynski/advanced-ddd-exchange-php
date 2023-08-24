<?php

namespace App\PromotionSaga;

use App\Kernel\IdentityId;

class NewClientPromotionCompleted {
    private IdentityId $identityId;

    public function __construct(IdentityId $identityId) {
        $this->identityId = $identityId;
    }

    public function getIdentityId(): IdentityId {
        return $this->identityId;
    }
}
