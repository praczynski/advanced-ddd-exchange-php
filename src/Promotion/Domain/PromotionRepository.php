<?php

namespace App\Promotion\Domain;

interface PromotionRepository {
    function save(Promotion $promotion);
}