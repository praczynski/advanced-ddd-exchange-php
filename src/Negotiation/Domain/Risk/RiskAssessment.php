<?php

namespace App\Negotiation\Domain\Risk;



//Agregat oceny ryzyka

use App\Negotiation\Domain\NegotiationId;
use App\Negotiation\Domain\Negotiator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "risk_assessments")]
class RiskAssessment {

    //cannot create primary key for embedded object
    //move to abstract entity
    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: RiskAssessmentNumber::class)]
    private RiskAssessmentNumber $riskAssessmentNumber;

    #[Embedded(class: Negotiator::class)]
    private Negotiator $negotiator;

    #[Embedded(class: RiskLevel::class)]
    private RiskLevel $riskLevel;

    #[OneToMany(mappedBy: 'riskAssessment', targetEntity: RiskLine::class, cascade: ["all"], orphanRemoval: true)]
    private Collection $riskLines;


    public function __construct(NegotiationId $negotiationId, RiskNegotiationValue $riskNegotiationValue, Negotiator $negotiator) {
        $this->riskAssessmentNumber = RiskAssessmentNumber::generate();
        $this->negotiator = $negotiator;
        $this->riskLevel = RiskLevel::LOW();
        $this->riskLines = new ArrayCollection();
        $this->riskLines->add(new RiskLine($this, $negotiationId, $riskNegotiationValue));
    }

    public function addNegotiation(NegotiationId $negotiationId, RiskNegotiationValue $riskNegotiationValue): void {
        $this->riskLines->add(new RiskLine($this, $negotiationId, $riskNegotiationValue));

        if ($this->riskLines->count() > 10 && $this->riskLines->count() < 50) {
            $this->riskLevel = RiskLevel::MEDIUM();
        } else if ($this->riskLines->count() > 50) {
            $this->riskLevel = RiskLevel::HIGH();
        }
    }

    public function changeRiskLevel(RiskLevel $riskLevel): void {
        if ($this->riskLevel !== $riskLevel) {
            $this->riskLevel = $riskLevel;
        }
    }
}