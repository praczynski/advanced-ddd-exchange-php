<?php

namespace App\Account\Domain\Exception;

use Exception;

class AccountNotFoundException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
