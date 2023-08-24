<?php

namespace App\Account\Domain\Events;

use App\Kernel\IdentityId;

class AccountActivated {
    private string $identityId;

    public function __construct(IdentityId $identityId) {
        $this->identityId = $identityId->toString();
    }

    public function getIdentityId(): string {
        return $this->identityId;
    }
}