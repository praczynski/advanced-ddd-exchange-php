<?php

namespace App\Kernel;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Exception;


#[Embeddable]
class Currency
{
    #[Column(name: "code", type: "string")]
    private string $code;

    public function __construct(string $code)
    {
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new Exception('Currency code must be a 3 letter code');
        }

        $this->code = $code;
    }
    public static function PLN():Currency
    {
        return new Currency("PLN");
    }

    public static function EUR():Currency
    {
        return new Currency("EUR");
    }

    public static function fromString(string $value): Currency
    {
        return new Currency($value);
    }
    public function equals(Currency $currency): bool
    {
        return $this->code === $currency->code;
    }

    public function toString(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}