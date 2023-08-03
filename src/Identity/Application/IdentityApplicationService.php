<?php

namespace App\Identity\Application;

use App\Identity\Domain\Event\IdentityCreated;
use App\Identity\Domain\IdentityDomainEventBus;

class IdentityApplicationService
{
    private IdentityDomainEventBus $eventBus;

    public function __construct(IdentityDomainEventBus $eventBus)
    {

        $this->eventBus = $eventBus;
    }

    public function createIdentity(CreateIdentityCommand $command): void
    {
        $this->eventBus->post(
            new IdentityCreated(
                $command->getIdentityId()->toString(),
                $command->getPesel(),
                $command->getFirstName(),
                $command->getSurname(),
                $command->getEmail()
            )
        );

    }
}