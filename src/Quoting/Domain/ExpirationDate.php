<?php

namespace App\Quoting\Domain;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class ExpirationDate
{
    #[Column(name: "expiration_date", type: "datetime")]
    private DateTime $expirationDate;

    private function __construct(DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    public static function oneDayExpirationDate(): self
    {
        return new self((new DateTime())->modify('+1 day'));
    }

    public static function oneHourExpirationDate(): self
    {
        return new self((new DateTime())->modify('+1 hour'));
    }
}
