<?php

namespace App\Kernel\Optional;

use phpDocumentor\Reflection\Types\Mixed_;

class Optional
{
    private mixed $value;

    private function __construct($value) {
        $this->value = $value;
    }

    public static function of($value): self {
        if ($value === null) {
            throw new InvalidArgumentException('Value cannot be null');
        }
        return new self($value);
    }

    public static function empty(): self {
        return new self(null);
    }

    public function isPresent(): bool {
        return $this->value !== null;
    }

    public function get() {
        if ($this->value === null) {
            throw new RuntimeException('No value present');
        }
        return $this->value;
    }

    public function orElse($defaultValue) {
        return $this->value !== null ? $this->value : $defaultValue;
    }
}