<?php

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;

#[Embeddable]
class Email
{
    #[Column(name: "value", type: "string")]
    private string $value;

    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException("Invalid email: " . $value);
        }
        $this->value = $value;
    }

    public function equals(Email $email): bool
    {
        return $this->value === $email->value;
    }

    private function isValid(string $value): bool
    {
        return trim($value) !== '' && filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
