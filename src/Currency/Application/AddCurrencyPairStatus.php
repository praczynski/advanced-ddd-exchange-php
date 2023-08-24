<?php

namespace App\Currency\Application;

use App\Currency\Domain\CurrencyPairId;

class AddCurrencyPairStatus {
    private const SUCCESS = "SUCCESS";
    private const CURRENCY_PAIR_ALREADY_EXISTS = "CURRENCY_PAIR_ALREADY_EXISTS";

    private string $status;
    private ?string $currencyPairId = null;

    private function __construct(string $status, ?string $currencyPairId = null) {
        $this->status = $status;
        $this->currencyPairId = $currencyPairId;
    }

    public static function createSuccessStatus(string $currencyPairId): self {
        return new self(self::SUCCESS, $currencyPairId);
    }

    public static function createCurrencyPairAlreadyExistsStatus(): self {
        return new self(self::CURRENCY_PAIR_ALREADY_EXISTS);
    }

    public static function createFailureStatus(string $message): self {
        return new self($message);
    }

    public function status(): string {
        return $this->status;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function getCurrencyPairId(): ?string {
        return $this->currencyPairId;
    }

    public function setCurrencyPairId(?string $currencyPairId): void {
        $this->currencyPairId = $currencyPairId;
    }

    public function currencyPairId(): ?string {
        return $this->currencyPairId;
    }
}
