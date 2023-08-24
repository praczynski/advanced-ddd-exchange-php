<?php

namespace App\Account\Domain\Policy;

use App\Account\Domain\Funds;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\Money;

class PLNTransactionLimitPolicy implements TransactionLimitPolicy {

    private Funds $upperTransactionLimit;
    private Funds $lowerTransactionLimit;

    public function __construct() {
        $this->upperTransactionLimit = Funds::fromValueAndCurrency(new BigDecimal("15000"), Currency::PLN());
        $this->lowerTransactionLimit = Funds::fromValueAndCurrency(new BigDecimal("50"), Currency::PLN());
    }

    public function withinTheLimit(Funds $funds): bool {
        return $funds->lessOrEqualsThan($this->upperTransactionLimit) && $funds->greaterOrEqualsThan($this->lowerTransactionLimit);
    }
}