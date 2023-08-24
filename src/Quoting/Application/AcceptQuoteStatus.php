<?php

namespace App\Quoting\Application;

class AcceptQuoteStatus {

    private const SUCCESS = "SUCCESS";
    private const QUOTE_NOT_FOUND = "QUOTE_NOT_FOUND";

    private string $status;

    private function __construct(string $status) {
        $this->status = $status;
    }

    public static function SUCCESS(): self {
        return new self(self::SUCCESS);
    }

    public static function QUOTE_NOT_FOUND(): self {
        return new self(self::QUOTE_NOT_FOUND);
    }

    public function getStatus(): string {
        return $this->status;
    }
}
