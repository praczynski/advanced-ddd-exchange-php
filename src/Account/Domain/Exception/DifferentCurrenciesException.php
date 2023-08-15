<?php

namespace App\Account\Domain\Exception;

class DifferentCurrenciesException extends \RuntimeException {
    public function __construct(string $firstCurrency, string $secondCurrency) {
        parent::__construct("The currencies $firstCurrency and $secondCurrency are different.");
    }
}