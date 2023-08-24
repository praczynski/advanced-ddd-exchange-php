<?php

namespace App\Identity\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Version;

#[Entity]
#[Table(name:"identities")]
class Identity
{
    #[Id, GeneratedValue, Column(type: "integer")]
    private $id;

    #[Version, Column(name: 'version', type: 'integer', nullable: false, options: ['default' => 0])]
    private int $version;

    #[Embedded(class: "App\Kernel\IdentityId")]
    private IdentityId $identityId;

    #[Embedded(class: "PESEL")]
    private PESEL $pesel;

    #[Embedded(class: "Email")]
    private Email $email;

    #[Embedded(class: "FirstName")]
    private FirstName $firstName;

    #[Embedded(class: "Surname")]
    private Surname $surname;

    public function __construct(IdentityId $identityId, PESEL $pesel, FirstName $firstName, Email $email, Surname $surname)
    {
        $this->identityId = $identityId;
        $this->pesel = $pesel;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->surname = $surname;
    }

    public function identityId(): IdentityId
    {
        return $this->identityId;
    }

    public function setVersion(int $int): void
    {
        $this->version = $int;
    }

    public function version(): int
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PESEL
     */
    public function getPesel(): PESEL
    {
        return $this->pesel;
    }

    /**
     * @param PESEL $pesel
     */
    public function setPesel(PESEL $pesel): void
    {
        $this->pesel = $pesel;
    }

}