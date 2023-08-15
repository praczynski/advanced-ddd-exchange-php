<?php

namespace App\Account\Domain\Events;

use App\Kernel\IdentityId;

class AccountActivated {
    private IdentityId $identityId;

    public function __construct(IdentityId $identityId) {
        $this->identityId = $identityId;
    }

    public function getIdentityId(): IdentityId {
        return $this->identityId;
    }
}