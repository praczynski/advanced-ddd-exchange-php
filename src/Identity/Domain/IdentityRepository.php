<?php

namespace App\Identity\Domain;

use App\Kernel\IdentityId;

interface IdentityRepository
{
    public function findByIdentityId(IdentityId $identityId): ?IdentityData;
    public function save(Identity $identity): void;

    public function existsByPesel(PESEL $pesel): bool;
    /**
     * @return IdentityId[]
     */
    public function findIdentityIds(): array;

}