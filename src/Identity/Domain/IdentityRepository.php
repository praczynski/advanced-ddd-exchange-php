<?php

namespace App\Identity\Domain;

interface IdentityRepository
{
    public function find(string $id): ?Identity;
    public function save(Identity $identity): void;
}