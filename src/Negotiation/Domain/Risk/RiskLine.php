<?php

namespace App\Negotiation\Domain\Risk;


use App\Negotiation\Domain\NegotiationId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name:"risk_lines")]
class RiskLine {

    //cannot create primary key for embedded object
    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: "RiskLineId")]
    private RiskLineId $riskLineId;

    #[Embedded(class: "App\Negotiation\Domain\NegotiationId")]
    private NegotiationId $negotiationId;

    #[Embedded(class: "RiskNegotiationValue")]
    private RiskNegotiationValue $riskNegotiationValue;

    #[ManyToOne(targetEntity: RiskAssessment::class, inversedBy: 'riskLines')]
    #[JoinColumn(name: 'risk_assessment_id', referencedColumnName: 'id', nullable: false)]
    private RiskAssessment $riskAssessment;


    public function __construct(RiskAssessment $riskAssessment, NegotiationId $negotiationId, RiskNegotiationValue $riskNegotiationValue) {
        $this->riskLineId = RiskLineId::generate();
        $this->negotiationId = $negotiationId;
        $this->riskNegotiationValue = $riskNegotiationValue;
        $this->riskAssessment = $riskAssessment;
    }

    public function getRiskLineId(): RiskLineId {
        return $this->riskLineId;
    }

    public function getNegotiationId(): NegotiationId {
        return $this->negotiationId;
    }

    public function getRiskNegotiationValue(): RiskNegotiationValue {
        return $this->riskNegotiationValue;
    }
}
