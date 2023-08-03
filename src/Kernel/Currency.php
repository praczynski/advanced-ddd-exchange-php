<?php

namespace App\Kernel;

class Currency
{
    private string $code;


    public function __construct(string $code)
    {
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new InvalidArgumentException('Currency code must be a 3 letter code');
        }

        $this->code = $code;
    }

    public function equals(Currency $currency): bool
    {
        return $this->code === $currency->code;
    }

    public function toString(): string
    {
        return $this->code;
    }
}