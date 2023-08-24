<?php

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;

#[Embeddable]
class Surname
{
    #[Column(name: "value", type: "string")]
    private string $value;

    public function __construct(string $value)
    {
        if(trim($value) === '')
        {
            throw new InvalidArgumentException("Invalid surname: " . $value);
        }
        $this->value = $value;
    }

}