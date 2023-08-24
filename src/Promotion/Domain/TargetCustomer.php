<?php

namespace App\Promotion\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Column;

#[Embeddable]
class TargetCustomer {

    #[Embedded(class: IdentityId::class)]
    private IdentityId $identityId;


    public function __construct(IdentityId $identityId) {
        $this->identityId = $identityId;
    }
}
