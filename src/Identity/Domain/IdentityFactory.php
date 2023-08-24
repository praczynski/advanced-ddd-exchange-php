<?php

namespace App\Identity\Domain;

use App\Kernel\IdentityId;

class IdentityFactory
{
    private IdentityRepository $identityRepository;

    /**
     * @param IdentityRepository $identityRepository
     */
    public function __construct(IdentityRepository $identityRepository)
    {
        $this->identityRepository = $identityRepository;
    }

    /**
     * @throws IdentityAlreadyExistsException
     */
    public function create(PESEL $pesel, FirstName $firstName, Surname $surname, Email $email): Identity
    {
        if ($this->identityRepository->existsByPesel($pesel)) {
            throw new IdentityAlreadyExistsException($pesel);
        }
        return new Identity(IdentityId::generate(), $pesel, $firstName, $email, $surname);
    }
}