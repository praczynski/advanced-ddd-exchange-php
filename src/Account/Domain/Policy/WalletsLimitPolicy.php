<?php

namespace App\Account\Domain\Policy;

interface WalletsLimitPolicy {
    public function isWalletsLimitExceeded(int $walletsQuantity): bool;
}