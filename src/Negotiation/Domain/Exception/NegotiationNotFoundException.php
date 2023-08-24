<?php

namespace App\Negotiation\Domain\Exception;

use Exception;

class NegotiationNotFoundException extends Exception {

    public function __construct(string $message) {
        parent::__construct($message);
    }

}