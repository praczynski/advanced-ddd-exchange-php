<?php

namespace App\Currency\Infrastructure;

use App\Currency\Domain\CurrencyPair;
use App\Currency\Domain\CurrencyPairData;
use App\Currency\Domain\CurrencyPairId;
use App\Currency\Domain\CurrencyPairRepository;
use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DBCurrencyPairRepository implements CurrencyPairRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(CurrencyPair $currencyPair): void
    {
        $this->entityManager->persist($currencyPair);
        $this->entityManager->flush();
    }

    public function findById(CurrencyPairId $currencyPairId): ?CurrencyPair
    {
        $currencyPairIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($currencyPairId, 'uuid');
        $queryString = "SELECT cp FROM " . CurrencyPair::class . " cp WHERE cp.currencyPairId.uuid = :currencyPairId";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter("currencyPairId", $currencyPairIdUuid);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function alreadyExists(Currency $baseCurrency, Currency $targetCurrency): bool
    {
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');

        $queryString = "SELECT count(cp) FROM " . CurrencyPair::class . " cp WHERE cp.baseCurrency.code = :baseCurrency AND cp.targetCurrency.code = :targetCurrency";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter("baseCurrency", $baseCurrencyCode);
        $query->setParameter("targetCurrency", $targetCurrencyCode);

        $count = $query->getSingleScalarResult();
        return $count > 0;
    }

    public function findByBaseCurrencyAndTargetCurrency(Currency $baseCurrency, Currency $targetCurrency): ?CurrencyPair
    {
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');
        $queryString = "SELECT cp FROM " . CurrencyPair::class . " cp WHERE cp.baseCurrency.code = :baseCurrency AND cp.targetCurrency.code = :targetCurrency";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter("baseCurrency", $baseCurrencyCode);
        $query->setParameter("targetCurrency", $targetCurrencyCode);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findDataByBaseCurrencyAndTargetCurrency(Currency $baseCurrency, Currency $targetCurrency): ?CurrencyPairData
    {
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');

        $queryString = "SELECT new App\\Currency\\Domain\\CurrencyPairData(
                        cp.currencyPairId.uuid,
                        cp.baseCurrency.code,
                        cp.targetCurrency.code,
                        cp.exchangeRate.baseRate.value,
                        cp.exchangeRate.adjustedRate.value 
                    )
                    FROM " . CurrencyPair::class . " cp 
                    WHERE cp.baseCurrency.code = :baseCurrency AND cp.targetCurrency.code = :targetCurrency";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter("baseCurrency", $baseCurrencyCode);
        $query->setParameter("targetCurrency", $targetCurrencyCode);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findAll(): array
    {
        $queryString = "SELECT new App\\Currency\\Domain\\CurrencyPairData(
                        cp.currencyPairId.uuid,
                        cp.baseCurrency.code,
                        cp.targetCurrency.code,
                        cp.exchangeRate.baseRate.value,
                        cp.exchangeRate.adjustedRate.value
                    )
                    FROM " . CurrencyPair::class . " cp";
        $query = $this->entityManager->createQuery($queryString);
        return $query->getResult();
    }

}

