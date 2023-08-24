<?php

namespace App\PromotionSaga;

use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\IdentityId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class NewClientPromotionRepository {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function save(NewClientPromotion $saga): void
    {
        $this->entityManager->persist($saga);
        $this->entityManager->flush();
    }

    public function findNewClientPromotion(IdentityId $identityId): ?NewClientPromotion
    {
        $identityIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($identityId, 'uuid');
        $queryString = "SELECT p FROM " . NewClientPromotion::class . " p WHERE p.identityId.uuid = :identityIdUuid";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('identityIdUuid', $identityIdUuid);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
