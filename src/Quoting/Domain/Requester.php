<?php

namespace App\Quoting\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class Requester
{
    #[Embedded(class: "App\Kernel\IdentityId")]
    private IdentityId $identityId;

    public function __construct(IdentityId $identityId)
    {
        $this->identityId = $identityId;
    }

    public static function fromIdentityId(IdentityId $identityId): self
    {
        return new self($identityId);
    }

    public function identityId(): IdentityId
    {
        return $this->identityId;
    }
}
