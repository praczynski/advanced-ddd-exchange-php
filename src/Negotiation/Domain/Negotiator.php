<?php

namespace App\Negotiation\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class Negotiator
{
    #[Embedded(class: "App\Kernel\IdentityId")]
    private IdentityId $identityId;

    private function __construct(IdentityId $identityId)
    {
        $this->identityId = $identityId;
    }

    public static function fromIdentity(IdentityId $identityId): Negotiator
    {
        return new Negotiator($identityId);
    }

    /**
     * It takes a callable function (converter) as an argument and applies it to the IdentityId instance.
     *
     * @param callable $converter
     * @return mixed
     */
    public function identity(callable $converter): mixed
    {
        return $converter($this->identityId);
    }
}