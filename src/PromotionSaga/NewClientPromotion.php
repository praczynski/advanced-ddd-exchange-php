<?php

namespace App\PromotionSaga;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "new_client_promotions")]
class NewClientPromotion {
    //cannot create primary key for embedded object

    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: IdentityId::class)]
    private IdentityId $identityId;

    #[Column(type: "boolean")]
    private bool $accountActivated;

    #[Column(type: "boolean")]
    private bool $negotiationCreated;

    public function __construct(IdentityId $identityId) {
        $this->identityId = $identityId;
        $this->accountActivated = false;
        $this->negotiationCreated = false;
    }

    public function isComplete(): bool {
        return $this->accountActivated && $this->negotiationCreated;
    }

    public function accountActivated(): void {
        $this->accountActivated = true;
    }

    public function identityId(): IdentityId {
        return $this->identityId;
    }

    public function negotiationCreated(): void {
        $this->negotiationCreated = true;
    }
}
