<?php

namespace App\Account\Application\Exception;

class WalletNotFoundException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
