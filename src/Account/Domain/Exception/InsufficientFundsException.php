<?php

namespace App\Account\Domain\Exception;

class InsufficientFundsException extends \RuntimeException {
    public function __construct(string $message = "Insufficient funds") {
        parent::__construct($message);
    }
}
