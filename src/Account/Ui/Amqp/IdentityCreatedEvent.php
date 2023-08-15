<?php

namespace App\Account\Ui\Amqp;

use App\Kernel\IdentityId;

class IdentityCreatedEvent
{
    private IdentityId $identityId;
    private string $pesel;
    private string $firstName;
    private string $surname;
    private string $email;

    public function __construct(
        IdentityId $identityId,
        string $pesel,
        string $firstName,
        string $surname,
        string $email
    ) {
        $this->identityId = $identityId;
        $this->pesel = $pesel;
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->email = $email;
    }

    public function getIdentityId(): IdentityId
    {
        return $this->identityId;
    }

    public function getPesel(): string
    {
        return $this->pesel;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
