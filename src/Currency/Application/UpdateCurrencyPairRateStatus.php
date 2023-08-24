<?php

namespace App\Currency\Application;

class UpdateCurrencyPairRateStatus {
    private const SUCCESS = "SUCCESS";
    private const CURRENCY_PAIR_NOT_FOUND = "CURRENCY_PAIR_NOT_FOUND";

    private string $status;

    private function __construct(string $status) {
        $this->status = $status;
    }

    public static function createSuccessStatus(): self {
        return new self(self::SUCCESS);
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

    public function status(): string {
        return $this->status;
    }
}
