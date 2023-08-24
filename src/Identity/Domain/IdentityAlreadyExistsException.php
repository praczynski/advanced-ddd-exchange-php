<?php

namespace App\Identity\Domain;

use Exception;

class IdentityAlreadyExistsException extends Exception
{
    public function __construct(PESEL $pesel)
    {
        parent::__construct("Identity with PESEL {$pesel->toString()} already exists.");
    }
}