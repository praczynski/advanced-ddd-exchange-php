<?php

namespace App\Negotiation\Domain\Supportedcurrency;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "supported_currencies")]
class SupportedCurrency {

    //cannot create primary key for embedded object
    //move to abstract entity
    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: SupportedCurrencyId::class)]
    private SupportedCurrencyId $supportedCurrencyId;

    #[Embedded(class: Currency::class)]
    private Currency $baseCurrency;

    #[Embedded(class: Currency::class)]
    private Currency $targetCurrency;

    #[Embedded(class: Rate::class)]
    private Rate $rate;

    #[Embedded(class: Status::class)]
    private Status $status;

    public function __construct(Currency $baseCurrency, Currency $targetCurrency, Rate $rate) {
        $this->supportedCurrencyId = SupportedCurrencyId::generate();
        $this->status = Status::ACTIVE();
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->rate = $rate;
    }

    public function setRate(Rate $rate): void {
        $this->rate = $rate;
    }

    public function getRate(): BigDecimal
    {
        return $this->rate->value( function($x) {
            return $x;
        });
    }

    public function activate(): void {
        $this->status = Status::ACTIVE();
    }

    public function deactivate(): void {
        $this->status = Status::INACTIVE();
    }
}