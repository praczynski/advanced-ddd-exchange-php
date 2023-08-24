<?php

namespace App\Account\Domain;

use App\Account\Domain\Events\AccountActivated;

interface AccountDomainEventBus {
    public function post(AccountActivated $accountActivated): void;
}