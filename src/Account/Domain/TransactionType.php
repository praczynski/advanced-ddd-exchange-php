<?php

namespace App\Account\Domain;

#[Embeddable]
class TransactionType {

    private const CARD = "CARD";
    private const CURRENCY_EXCHANGE = "CURRENCY_EXCHANGE";
    private const TRANSFER_BETWEEN_ACCOUNTS_DEPOSIT = "TRANSFER_BETWEEN_ACCOUNTS_DEPOSIT";
    private const TRANSFER_BETWEEN_ACCOUNTS_WITHDRAW = "TRANSFER_BETWEEN_ACCOUNTS_WITHDRAW";
    private const WITHDRAW = "WITHDRAW";
    private const DEPOSIT  = "DEPOSIT ";

    private string $value;

    private function __construct(string $type) {
        $this->value = $type;
    }

    public static function CARD(): self {
        return new self(self::CARD);
    }

    public static function CURRENCY_EXCHANGE(): self {
        return new self(self::CURRENCY_EXCHANGE);
    }
    public static function TRANSFER_BETWEEN_ACCOUNTS_DEPOSIT(): self {
        return new self(self::TRANSFER_BETWEEN_ACCOUNTS_DEPOSIT);
    }

    public static function TRANSFER_BETWEEN_ACCOUNTS_WITHDRAW(): self {
        return new self(self::TRANSFER_BETWEEN_ACCOUNTS_WITHDRAW);
    }

    public static function WITHDRAW(): self {
        return new self(self::WITHDRAW);
    }

    public static function DEPOSIT(): self {
        return new self(self::DEPOSIT);
    }

    public function equals(TransactionType $other): bool {
        return $this->value === $other->value;
    }
}
