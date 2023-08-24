<?php

namespace App\Promotion\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use App\Promotion\Domain\Promotion;
use App\Promotion\Domain\PromotionRepository;

class DBPromotionRepository implements PromotionRepository {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function save(Promotion $promotion): void {
        $this->entityManager->persist($promotion);
        $this->entityManager->flush();
    }
}
