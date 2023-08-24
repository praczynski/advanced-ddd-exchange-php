<?php

namespace App\Identity\Application;

use App\Kernel\IdentityId;

class GetIdentityStatus
{

    private string $pesel;
    private string $firstName;
    private string $surname;
    private string $identityId;
    private string $email;

    public function __construct(string $identityId, string $firstName, string $surname, string $pesel, string $email)
    {
        $this->identityId = $identityId;
        $this->pesel = $pesel;
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->email = $email;
    }

    public function getIdentityId(): string
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