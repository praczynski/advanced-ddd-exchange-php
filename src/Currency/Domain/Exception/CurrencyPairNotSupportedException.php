<?php

namespace App\Currency\Domain\Exception;

use Exception;

class CurrencyPairNotSupportedException extends Exception {

    public function __construct($baseCurrency, $targetCurrency) {
        parent::__construct("Currency pair not supported: " . $baseCurrency . " -> " . $targetCurrency);
    }
}
