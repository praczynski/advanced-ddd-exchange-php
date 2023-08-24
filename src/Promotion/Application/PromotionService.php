<?php

namespace App\Promotion\Application;

use App\Kernel\IdentityId;
use App\Promotion\Domain\Promotion;
use App\Promotion\Domain\PromotionRepository;
use App\Promotion\Domain\PromotionType;
use App\Promotion\Domain\TargetCustomer;
use Doctrine\ORM\EntityManagerInterface;

class PromotionService {

    private PromotionRepository $promotionRepository;

    public function __construct(PromotionRepository $promotionRepository) {
        $this->promotionRepository = $promotionRepository;
    }

    public function createNewTraderPromotion(IdentityId $identityId): void {
        $promotion = new Promotion(new TargetCustomer($identityId), PromotionType::NEW_TRADER());
        $this->promotionRepository->save($promotion);
    }
}
