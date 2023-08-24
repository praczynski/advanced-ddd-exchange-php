<?php

namespace App\Account\Domain\Policy;



use App\Account\Domain\Funds;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

class EuroTransactionLimitPolicy implements TransactionLimitPolicy {
    private Funds $upperTransactionLimit;
    private Funds $lowerTransactionLimit;

    public function __construct() {
        $this->upperTransactionLimit = Funds::fromValueAndCurrency(new BigDecimal("4000"), Currency::EUR());
        $this->lowerTransactionLimit = Funds::fromValueAndCurrency(new BigDecimal("12"), Currency::EUR());
    }

    public function withinTheLimit(Funds $funds): bool {
        return $funds->lessOrEqualsThan($this->upperTransactionLimit) && $funds->greaterOrEqualsThan($this->lowerTransactionLimit);
    }
}
