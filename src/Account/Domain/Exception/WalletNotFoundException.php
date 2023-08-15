<?php

namespace App\Account\Domain\Exception;

class WalletNotFoundException extends \RuntimeException {
    public function __construct($message = "Wallet not found") {
        parent::__construct($message);
    }
}
