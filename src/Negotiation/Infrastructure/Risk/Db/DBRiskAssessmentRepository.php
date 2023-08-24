<?php

namespace App\Negotiation\Infrastructure\Risk\Db;

use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Negotiation\Domain\Negotiator;
use App\Negotiation\Domain\Risk\RiskAssessment;
use App\Negotiation\Domain\Risk\RiskAssessmentNumber;
use App\Negotiation\Domain\Risk\RiskAssessmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DBRiskAssessmentRepository implements RiskAssessmentRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(RiskAssessment $riskAssessment): void
    {
        $this->entityManager->persist($riskAssessment);
        $this->entityManager->flush();
    }

    public function findByNegotiator(Negotiator $negotiator): ?RiskAssessment
    {
        $identityId = ValueObjectPropertyGetter::getValueUsingReflection($negotiator, "identityId");
        $identityIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($identityId, "uuid");

        $queryString = "SELECT r FROM " . RiskAssessment::class . " r WHERE r.negotiator.identityId.uuid = :identityIdUuid";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('identityIdUuid', $identityIdUuid);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findByRiskAssessmentNumber(RiskAssessmentNumber $riskAssessmentNumber): ?RiskAssessment
    {
        $riskAssessmentNumberUuid = ValueObjectPropertyGetter::getValueUsingReflection($riskAssessmentNumber, "uuid");
        $queryString = "SELECT r FROM " . RiskAssessment::class . " r WHERE r.riskAssessmentNumber.uuid = :riskAssessmentNumberUuid ";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('riskAssessmentNumberUuid ', $riskAssessmentNumberUuid );

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}