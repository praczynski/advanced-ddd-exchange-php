<?php

namespace App\Account\Domain\Exception;

use Exception;

class AccountAlreadyExistsException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
