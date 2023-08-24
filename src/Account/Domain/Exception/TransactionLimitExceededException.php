<?php

namespace App\Account\Domain\Exception;

class TransactionLimitExceededException extends \Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
