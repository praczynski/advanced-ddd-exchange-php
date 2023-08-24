<?php

namespace App\Quoting\Domain\Exception;

use Exception;

class QuoteNotFoundException extends Exception {
    public function __construct(string $message) {
        parent::__construct($message);
    }
}
