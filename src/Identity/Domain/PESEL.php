<?php

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;

#[Embeddable]
class PESEL {

    #[Column(name: "value", type: "string")]
    private string $value;

    public function __construct(string $value) {
        if (!$this->isValidPesel($value)) {
            throw new InvalidArgumentException("Invalid PESEL.");
        }
        $this->value = $value;
    }

    public function equals(self $pesel): bool {
        return $this->value === $pesel->value;
    }

    public function equalsString(string $pesel): bool {
        return $this->value === $pesel;
    }

    private function isValidPesel(?string $pesel): bool {
        if ($pesel === null || strlen($pesel) != 11) {
            return false;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;

        for ($i = 0; $i < count($weights); $i++) {
            $sum += $weights[$i] * intval($pesel[$i]);
        }

        $checkSum = (10 - ($sum % 10)) % 10;

        return $checkSum === intval($pesel[10]);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
