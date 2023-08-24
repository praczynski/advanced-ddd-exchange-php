<?php

namespace App\Account\Application\Exception;

use RuntimeException;

class WalletNotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
