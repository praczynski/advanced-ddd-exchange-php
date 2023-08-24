<?php

namespace App\Negotiation\Infrastructure\Db;


use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Negotiation\Domain\Negotiation;
use App\Negotiation\Domain\NegotiationId;
use App\Negotiation\Domain\NegotiationRepository;
use App\Negotiation\Domain\Negotiator;
use App\Negotiation\Domain\ProposedExchangeAmount;
use App\Negotiation\Domain\Status;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Exception;

class DBNegotiationRepository implements NegotiationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Negotiation $negotiation): void
    {
        $this->entityManager->persist($negotiation);
        $this->entityManager->flush();
    }


    public function findById(NegotiationId $id): ?Negotiation
    {
        $negotiationIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($id, 'uuid');
        $queryString = "SELECT n FROM " . Negotiation::class . " n WHERE n.negotiationId.uuid = :negotiationIdUuid";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('negotiationIdUuid', $negotiationIdUuid);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function alreadyExistsActiveNegotiationForNegotiator(Negotiator $negotiator, Currency $baseCurrency, Currency $targetCurrency, BigDecimal $proposedRate, ProposedExchangeAmount $proposedExchangeAmount): bool
    {


        $negotiationIdentityId = ValueObjectPropertyGetter::getValueUsingReflection($negotiator, 'identityId');
        $negotiationIdentityUuid = ValueObjectPropertyGetter::getValueUsingReflection($negotiationIdentityId, 'uuid');
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');
        $proposedRateValue = ValueObjectPropertyGetter::getValueUsingReflection($proposedRate, 'value');
        //TODO WHY THIS IS NOT WORKING Without this copy?
        $proposedExchangeAmountCopy=$proposedExchangeAmount;
        $proposedExchangeAmountBigDecimal = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeAmount, 'value');
        $proposedExchangeAmount = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeAmountBigDecimal, 'value');

        $proposedExchangeCurrency = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeAmountCopy, 'currency');
        $proposedExchangeCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeCurrency, 'code');

        $queryString = "SELECT n FROM " . Negotiation::class . " n WHERE n.negotiator.identityId.uuid = :negotiationIdentityUuid AND n.baseCurrency.code = :baseCurrencyCode AND n.targetCurrency.code = :targetCurrencyCode AND n.negotiationRate.proposedRate.value = :proposedRateValue AND n.proposedExchangeAmount.value.value = :proposedExchangeAmountValue AND n.proposedExchangeAmount.currency.code = :proposedExchangeAmountCurrencyCode  AND n.status.status = :status";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('negotiationIdentityUuid', $negotiationIdentityUuid);
        $query->setParameter('baseCurrencyCode', $baseCurrencyCode);
        $query->setParameter('targetCurrencyCode', $targetCurrencyCode);
        $query->setParameter('proposedRateValue', $proposedRateValue);
        $query->setParameter('proposedExchangeAmountValue', $proposedExchangeAmount);
        $query->setParameter('proposedExchangeAmountCurrencyCode', $proposedExchangeCurrencyCode);
        $query->setParameter('status', Status::PENDING()->toString());

        $negotiations = $query->getResult();
        return !empty($negotiations);
    }

    public function findApprovedRateById(NegotiationId $negotiationId): ?BigDecimal
    {

        $negotiationIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($negotiationId, 'uuid');
        $queryString = "SELECT n.negotiationRate.proposedRate.value FROM " . Negotiation::class . " n WHERE n.negotiationId.uuid = :negotiationIdUuid AND n.status.status = :status";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('negotiationIdUuid', $negotiationIdUuid);
        $query->setParameter('status', Status::APPROVED()->toString());

        try {
            $result = $query->getSingleResult();
            return BigDecimal::fromBrickBigDecimal($result['negotiationRate.proposedRate.value']);

        } catch (Exception $e) {
            return null;
        }
    }

    public function findAcceptedActiveNegotiation(Negotiator $negotiator, Currency $baseCurrency, Currency $targetCurrency, ProposedExchangeAmount $proposedExchangeAmount): ?BigDecimal
    {
        $negotiationIdentityId = ValueObjectPropertyGetter::getValueUsingReflection($negotiator, 'identityId');
        $negotiationIdentityUuid = ValueObjectPropertyGetter::getValueUsingReflection($negotiationIdentityId, 'uuid');
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');
        $proposedExchangeAmountCopy=$proposedExchangeAmount;
        $proposedExchangeAmountBigDecimal = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeAmount, 'value');
        $proposedExchangeAmount = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeAmountBigDecimal, 'value');

        $proposedExchangeCurrency = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeAmountCopy, 'currency');
        $proposedExchangeCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($proposedExchangeCurrency, 'code');


        $queryString = "SELECT n.negotiationRate.proposedRate.value FROM " . Negotiation::class . " n WHERE n.negotiator.identityId.uuid = :negotiationIdentityUuid AND n.baseCurrency.code = :baseCurrencyCode AND n.targetCurrency.code = :targetCurrencyCode AND n.proposedExchangeAmount.value.value = :proposedExchangeAmountValue AND n.proposedExchangeAmount.currency.code = :proposedExchangeAmountCurrencyCode AND n.status.status = :status";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('negotiationIdentityUuid', $negotiationIdentityUuid);
        $query->setParameter('baseCurrencyCode', $baseCurrencyCode);
        $query->setParameter('targetCurrencyCode', $targetCurrencyCode);
        $query->setParameter('proposedExchangeAmountValue', $proposedExchangeAmount);
        $query->setParameter('proposedExchangeAmountCurrencyCode', $proposedExchangeCurrencyCode);
        $query->setParameter('status', Status::APPROVED()->toString());

        try {
            $result = $query->getSingleResult();
            return BigDecimal::fromBrickBigDecimal($result['negotiationRate.proposedRate.value']);
        } catch (Exception $e) {
            return null;
        }
    }

    function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    function commit(): void
    {
        $this->entityManager->commit();
    }

    function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
