<?php

namespace App\Account\Domain\Policy;

use App\Account\Domain\Funds;

class WithoutTransactionLimitPolicy implements TransactionLimitPolicy {

    public function withinTheLimit(Funds $funds): bool {
        return true;
    }
}