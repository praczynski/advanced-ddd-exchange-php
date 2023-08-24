<?php

namespace App\Quoting\Infrastructure\Db;

use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\Currency;
use App\Quoting\Domain\Exception\QuoteNotFoundException;
use App\Quoting\Domain\MoneyToExchange;
use App\Quoting\Domain\Quote;
use App\Quoting\Domain\QuoteNumber;
use App\Quoting\Domain\QuoteRepository;
use App\Quoting\Domain\QuoteStatus;
use App\Quoting\Domain\Requester;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DBQuoteRepository implements QuoteRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Quote $quote): void
    {
        $this->entityManager->persist($quote);
        $this->entityManager->flush();
    }

    public function findActiveQuote(Requester $requester, Currency $currencyToSell, Currency $currencyToBuy, MoneyToExchange $moneyToExchange): ?Quote
    {
        $requesterIdentityId = ValueObjectPropertyGetter::getValueUsingReflection($requester, 'identityId');
        $requesterIdentityIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($requesterIdentityId, 'uuid');
        $currencyToSellCode = ValueObjectPropertyGetter::getValueUsingReflection($currencyToSell, 'code');
        $currencyToBuyCode = ValueObjectPropertyGetter::getValueUsingReflection($currencyToBuy, 'code');

        $moneyToExchangeValue = ValueObjectPropertyGetter::getValueUsingReflection($moneyToExchange, 'value');
        $moneyToExchangeValueBigDecimal = ValueObjectPropertyGetter::getValueUsingReflection($moneyToExchangeValue, 'value');
        $moneyToExchangeCurrency = ValueObjectPropertyGetter::getValueUsingReflection($moneyToExchange, 'currency');
        $moneyToExchangeCurrencyCode = ValueObjectPropertyGetter::getValueUsingReflection($moneyToExchangeCurrency, 'code');

        $queryString = "SELECT q FROM " . Quote::class . " q WHERE q.requester.identityId.uuid = :requesterUuid AND q.bestExchangeRate.currencyToSell.code = :currencyToSellCode AND q.bestExchangeRate.currencyToBuy.code = :currencyToBuyCode AND q.moneyToExchange.value.value = :moneyToExchangeValue AND q.moneyToExchange.currency.code = :moneyToExchangeCurrencyCode AND q.quoteStatus.status = :status";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('requesterUuid', $requesterIdentityIdUuid);
        $query->setParameter('currencyToSellCode', $currencyToSellCode);
        $query->setParameter('currencyToBuyCode', $currencyToBuyCode);
        $query->setParameter('moneyToExchangeValue', $moneyToExchangeValueBigDecimal);
        $query->setParameter('moneyToExchangeCurrencyCode', $moneyToExchangeCurrencyCode);
        $query->setParameter('status', QuoteStatus::PREPARED()->toString());

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findActiveQuoteByNumber(QuoteNumber $quoteNumber): ?Quote
    {
        $quoteIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($quoteNumber, 'uuid');

        $queryString = "SELECT q FROM " . Quote::class . " q WHERE q.quoteNumber.uuid = :quoteId AND q.quoteStatus.status = :quoteStatus";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('quoteId', $quoteIdUuid);
        $query->setParameter('quoteStatus', QuoteStatus::PREPARED()->toString());

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @throws QuoteNotFoundException
     * @throws NonUniqueResultException
     */
    public function getQuote(QuoteNumber $quoteNumber): Quote
    {
        $quoteIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($quoteNumber, 'uuid');

        $queryString = "SELECT q FROM " . Quote::class . " q WHERE q.quoteNumber.uuid = :quoteId";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('quoteId', $quoteIdUuid);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new QuoteNotFoundException($quoteNumber->toString());
        }
    }

    public function findAllQuotesToExpire(): array
    {
        $queryString = "SELECT q FROM " . Quote::class . " q WHERE q.expirationDate.expirationDate < :currentDate AND q.quoteStatus.status = :status";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('status', QuoteStatus::PREPARED()->toString());
        $query->setParameter('currentDate', new DateTime());

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return [];
        }
    }

    public function findAllQuotesToExpireByCurrency(Currency $currencyToSell, Currency $currencyToBuy): array
    {
        $currencyToSellCode = ValueObjectPropertyGetter::getValueUsingReflection($currencyToSell, 'code');
        $currencyToBuyCode = ValueObjectPropertyGetter::getValueUsingReflection($currencyToBuy, 'code');
        $queryString = "SELECT q FROM " . Quote::class . " q WHERE q.bestExchangeRate.currencyToSell.code = :currencyToSellCode AND q.bestExchangeRate.currencyToBuy.code = :currencyToBuyCode AND q.quoteStatus.status = :status";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('status', QuoteStatus::PREPARED()->toString());
        $query->setParameter('currencyToSellCode', $currencyToSellCode);
        $query->setParameter('currencyToBuyCode', $currencyToBuyCode);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return [];
        }
    }
}