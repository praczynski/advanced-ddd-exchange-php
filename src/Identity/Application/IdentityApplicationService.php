<?php

namespace App\Identity\Application;

use App\Identity\Domain\Email;
use App\Identity\Domain\FirstName;
use App\Identity\Domain\Identity;
use App\Identity\Domain\IdentityAlreadyExistsException;
use App\Identity\Domain\IdentityFactory;
use App\Identity\Domain\IdentityRepository;
use App\Identity\Domain\PESEL;
use App\Identity\Domain\Surname;
use App\Kernel\IdentityId;
use Psr\Log\LoggerInterface;

class IdentityApplicationService
{
    private IdentityFactory $identityFactory;
    private LoggerInterface $logger;
    private IdentityRepository $identityRepository;

    public function __construct(IdentityFactory $identityFactory,
                                LoggerInterface $logger, IdentityRepository $identityRepository)
    {
        $this->identityFactory = $identityFactory;
        $this->logger = $logger;
        $this->identityRepository = $identityRepository;
    }

    public function createIdentity(CreateIdentityCommand $command): CreateIdentityStatus
    {
        try {
            $identity = $this->identityFactory->create(
                new PESEL($command->getPesel()),
                new FirstName($command->getFirstName()),
                new Surname($command->getSurname()),
                new Email($command->getEmail()));

            $this->identityRepository->save($identity);
            return CreateIdentityStatus::prepareSuccessStatus($identity->identityId());
        } catch (IdentityAlreadyExistsException $e) {
            $this->logger->error('Identity already exists', [
                'exception' => $e,
            ]);
            return CreateIdentityStatus::prepareExistsStatus();
        }
    }

    /**
     * @return IdentityId[]
     */
    public function getAllIdentityIds(): array {
        return $this->identityRepository->findIdentityIds();
    }

    public function getIdentity(IdentityId $identityId): GetIdentityStatus {
       $identity = $this->identityRepository->findByIdentityId($identityId);

        if (!$identity) {
            throw new \RuntimeException("Identity not found");
        }

        return new GetIdentityStatus(
            $identity->getIdentityId(),
            $identity->getFirstName(),
            $identity->getSurname(),
            $identity->getPesel(),
            $identity->getEmail()
        );
    }
}