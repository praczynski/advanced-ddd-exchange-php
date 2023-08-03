<?php

namespace App\Identity\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
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

    public function __construct()
    {
        $this->version = 0;
        $this->identityId = IdentityId::generate();
        $this->pesel = new PESEL('73052358124');
        $this->email = new Email('lsutula@coztymit.pl');
        $this->firstName = new FirstName('Łukasz');
        $this->surname = new Surname('Sutuła');

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

}