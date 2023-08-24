<?php

namespace App\Account\Domain\Exception;

use RuntimeException;

class WalletNotFoundException extends RuntimeException {
    public function __construct($message = "Wallet not found") {
        parent::__construct($message);
    }
}
