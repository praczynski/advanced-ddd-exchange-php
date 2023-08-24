<?php

namespace App\Account\Domain\Exception;

use RuntimeException;

class DifferentCurrenciesException extends RuntimeException {
    public function __construct() {
        parent::__construct("Currencies must be the same");
    }
}