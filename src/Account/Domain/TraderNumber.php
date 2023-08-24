<?php

namespace App\Account\Domain;



use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use RuntimeException;

#[Embeddable]
class TraderNumber {

    #[Column(name: "value", type: "string")]
    private string $value;

    public function __construct(string $number) {
        if (!self::isValidTraderNumber($number)) {
            throw new RuntimeException("Incorrect trader number");
        }
        $this->value = $number;
    }
    public static function fromString(string $number): TraderNumber
    {
        return new TraderNumber($number);
    }

    public static function generateNewNumber(): self {

        $letters = implode('', array_map(function ($n) { return chr(mt_rand(65, 90)); }, range(1, 3)));

        $currentDate = new DateTime();
        $day = $currentDate->format('d');
        $year = $currentDate->format('Y');

        $number = mt_rand(0, 999);  // generate three digits number
        $digits = str_pad($number, 3, '0', STR_PAD_LEFT);  // leading zeroes if number < 100

        $traderNumber = $letters . "-" . $day . "-" . $year . "-" . $digits;

        return new self($traderNumber);
    }
    public static function isValidTraderNumber(string $traderNumber): bool {
        if (strlen($traderNumber) != 15) {
            return false;
        }

        // The pattern is: 3 uppercase letters, hyphen, 2 digits day, hyphen, 4 digits year, hyphen, 3 digits
        $pattern = "/^[A-Z]{3}-\d{2}-\d{4}-\d{3}$/";

        return preg_match($pattern, $traderNumber) === 1;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
