<?php

namespace App\Negotiation\Domain;

use App\Kernel\Currency;
use App\Negotiation\Domain\Event\NegotiationApproved;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name:"negotiations")]
class Negotiation {

    //cannot create primary key for embedded object

    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: "NegotiationId")]
    private NegotiationId $negotiationId;

    #[Embedded(class: "Negotiator")]
    private Negotiator $negotiator;

    #[Embedded(class: "Operator")]
    private Operator $operator;

    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $baseCurrency;

    #[Embedded(class: "App\Kernel\Currency")]
    private Currency $targetCurrency;

    #[Embedded(class: "ProposedExchangeAmount")]
    private ProposedExchangeAmount $proposedExchangeAmount;

    #[Embedded(class: "NegotiationRate")]
    private NegotiationRate $negotiationRate;

    #[Embedded(class: "Status")]
    private Status $status;

    #[Embedded(class: "ExpirationDate",  columnPrefix: false)]
    private ExpirationDate $expirationDate;


    public function __construct(Negotiator $negotiator, ProposedExchangeAmount $proposedExchangeAmount, Currency $baseCurrency, Currency $targetCurrency, NegotiationRate $negotiationRate)
    {
        $this->negotiationId = NegotiationId::generate();
        $this->negotiator = $negotiator;
        $this->proposedExchangeAmount = $proposedExchangeAmount;
        $this->status = Status::PENDING();
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->negotiationRate = $negotiationRate;
    }

    public function tryAutomaticApprove(iterable $negotiationAmountAutomaticApprovePolicies): AutomaticNegotiationStatus
    {
        foreach ($negotiationAmountAutomaticApprovePolicies as $policy) {
            if ($policy->isApplicable($this->proposedExchangeAmount) && $policy->shouldApprove($this->proposedExchangeAmount, $this->negotiationRate->differenceInPercentage())) {
                $this->status = Status::APPROVED();
                $this->expirationDate = ExpirationDate::oneHourExpirationDate();
                break;
            }
        }

        return $this->status->isApproved() ? AutomaticNegotiationStatus::APPROVED() : AutomaticNegotiationStatus::PENDING();
    }

    public function approve(OperatorId $operatorId, iterable $eventBuses): void
    {
        $this->operator = new Operator($operatorId);
        $this->status = Status::APPROVED();
        $this->expirationDate = ExpirationDate::oneHourExpirationDate();

        foreach ($eventBuses as $eventBus) {
            $eventBus->postNegotiationApproved(new NegotiationApproved($this->negotiationId, $this->negotiator, $this->proposedExchangeAmount->asMoney()));
        }
    }

    public function reject(OperatorId $operatorId): void
    {
        $this->operator = new Operator($operatorId);
        $this->status = Status::REJECTED();
        $this->expirationDate = ExpirationDate::expiredDate();
    }

    public function isApproved(): bool
    {
        return $this->status->isApproved();
    }

    public function negotiationId(): NegotiationId
    {
        return $this->negotiationId;
    }

    public function proposedExchangeAmount(): ProposedExchangeAmount
    {
        return $this->proposedExchangeAmount;
    }

    public function negotiator(): Negotiator
    {
        return $this->negotiator;
    }
}
