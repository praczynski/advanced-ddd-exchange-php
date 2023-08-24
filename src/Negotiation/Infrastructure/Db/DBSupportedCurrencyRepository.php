<?php

namespace App\Negotiation\Infrastructure\Db;

use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\Currency;
use App\Negotiation\Domain\Supportedcurrency\Status;
use App\Negotiation\Domain\Supportedcurrency\SupportedCurrency;
use App\Negotiation\Domain\Supportedcurrency\SupportedCurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DBSupportedCurrencyRepository implements SupportedCurrencyRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(SupportedCurrency $supportedCurrency): void
    {
        $this->entityManager->persist($supportedCurrency);
        $this->entityManager->flush();
    }

    public function findByCurrency(Currency $baseCurrency, Currency $targetCurrency): ?SupportedCurrency
    {
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');

        $queryString = "SELECT sc FROM " . SupportedCurrency::class . " sc WHERE sc.baseCurrency.code = :baseCurrencyCode AND sc.targetCurrency.code = :targetCurrencyCode";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('baseCurrencyCode', $baseCurrencyCode);
        $query->setParameter('targetCurrencyCode', $targetCurrencyCode);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findActiveByCurrency(Currency $baseCurrency, Currency $targetCurrency): ?SupportedCurrency
    {
        $baseCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($baseCurrency, 'code');
        $targetCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($targetCurrency, 'code');


        $queryString = "SELECT sc FROM " . SupportedCurrency::class . " sc WHERE sc.baseCurrency.code = :baseCurrencyCode AND sc.targetCurrency.code = :targetCurrencyCode AND sc.status.status = :status";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('baseCurrencyCode', $baseCurrencyCode);
        $query->setParameter('targetCurrencyCode', $targetCurrencyCode);
        $query->setParameter('status', Status::ACTIVE()->toString());

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

}
