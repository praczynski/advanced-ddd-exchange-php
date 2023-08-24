<?php

namespace App\Account\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;

#[Embeddable]
class Trader {

    #[Embedded(class: "TraderNumber")]
    private TraderNumber $number;

    #[Embedded(class: "App\Kernel\IdentityId")]
    private IdentityId $identityId;

    public function __construct(TraderNumber $number, IdentityId $identityId) {
        $this->number = $number;
        $this->identityId = $identityId;
    }

    public function identity(callable $converter) {
        return $converter($this->identityId);
    }
}
