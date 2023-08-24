<?php

namespace App\Identity\Infrastructure;

use App\Identity\Domain\Identity;
use App\Identity\Domain\IdentityData;
use App\Identity\Domain\IdentityRepository;
use App\Identity\Domain\PESEL;
use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\IdentityId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class DBIdentityRepository implements IdentityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByIdentityId(IdentityId $identityId): ?IdentityData
    {

        $dql = "SELECT NEW App\Identity\Domain\IdentityData(
                i.identityId.uuid, 
                i.firstName.value, 
                i.surname.value, 
                i.pesel.value, 
                i.email.value
            ) 
            FROM " . Identity::class . " i 
            WHERE i.identityId.uuid = :identityId";

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('identityId', $identityId->toString());

        try {
            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    public function save(Identity $identity): void
    {
        $this->entityManager->persist($identity);
        $this->entityManager->flush();
    }

    public function existsByPesel(PESEL $pesel): bool
    {
        $peselValue = ValueObjectPropertyGetter::getValueUsingReflection($pesel, 'value');

        $dql = "SELECT i FROM " . Identity::class . " i WHERE i.pesel.value = :peselValue";
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('peselValue', $peselValue);

        $result = $query->getResult();
        return !empty($result);
    }

    public function findIdentityIds(): array
    {
        $dql = "SELECT i.identityId.uuid FROM " . Identity::class . " i";
        $query = $this->entityManager->createQuery($dql);
        return $query->getResult();
    }
}