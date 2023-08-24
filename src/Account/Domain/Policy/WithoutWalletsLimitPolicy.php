<?php

namespace App\Account\Domain\Policy;

class WithoutWalletsLimitPolicy implements WalletsLimitPolicy {

    public function isWalletsLimitExceeded(int $walletsQuantity): bool
    {
        return false;
    }
}

