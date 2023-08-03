<?php

namespace App\Identity\Infrastructure;

use App\Identity\Domain\Identity;
use App\Identity\Domain\IdentityRepository;
use Doctrine\ORM\EntityManagerInterface;

class DBIdentityRepository implements IdentityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $id): ?Identity
    {
        return $this->entityManager->getRepository(Identity::class)->find($id);
    }

    public function save(Identity $identity): void
    {

        $this->entityManager->persist($identity);
        $this->entityManager->flush();
    }
}