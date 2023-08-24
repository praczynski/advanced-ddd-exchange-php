<?php

namespace App\Identity\Domain;

use App\Identity\Domain\Event\IdentityCreated;

interface IdentityDomainEventBus
{
    function post(IdentityCreated $event): void;

}