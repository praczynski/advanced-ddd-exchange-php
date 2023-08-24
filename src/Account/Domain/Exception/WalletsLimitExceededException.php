<?php

namespace App\Account\Domain\Exception;

use Exception;

class WalletsLimitExceededException extends Exception {

    public function __construct($message = "Wallet limit exceeded") {
        parent::__construct($message);
    }
}