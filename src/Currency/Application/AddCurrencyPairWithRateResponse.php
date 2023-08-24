<?php

namespace App\Currency\Application;

class AddCurrencyPairWithRateResponse {
    private const SUCCESS = "SUCCESS";
    private const CURRENCY_PAIR_NOT_FOUND = "CURRENCY_PAIR_NOT_FOUND";
    private const CURRENCY_PAIR_ALREADY_EXISTS = "CURRENCY_PAIR_ALREADY_EXISTS";
    private const CURRENCY_PAIR_NOT_SUPPORTED = "CURRENCY_PAIR_NOT_SUPPORTED";

    private string $status;

    private function __construct(string $status) {
        $this->status = $status;
    }

    public static function createSuccessStatus(): self {
        return new self(self::SUCCESS);
    }

    public static function createNorSupportedStatus(): self {
        return new self(self::CURRENCY_PAIR_NOT_SUPPORTED);
    }

    public static function createAlreadyExistsStatus(): self {
        return new self(self::CURRENCY_PAIR_ALREADY_EXISTS);
    }

    public static function createCurrencyPairNotFoundStatus(): self {
        return new self(self::CURRENCY_PAIR_NOT_FOUND);
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }
}
