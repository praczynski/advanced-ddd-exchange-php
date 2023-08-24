<?php

namespace App\Account\Domain\Policy;

use App\Account\Domain\Funds;

interface TransactionLimitPolicy {
    public function withinTheLimit(Funds $funds): bool;
}
