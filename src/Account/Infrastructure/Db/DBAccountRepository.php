<?php

namespace App\Account\Infrastructure\Db;

use App\Account\Domain\Account;
use App\Account\Domain\AccountNumber;
use App\Account\Domain\AccountRepository;
use App\Account\Domain\TraderNumber;
use App\Infrastructure\reflection\ValueObjectPropertyGetter;
use App\Kernel\IdentityId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DBAccountRepository implements AccountRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(AccountNumber $accountNumber): ?Account
    {
        return $this->entityManager->find(Account::class, $accountNumber);
    }

    public function save(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function isThereAccountFor(IdentityId $identityId): bool
    {
        $identityIdUuid = ValueObjectPropertyGetter::getValueUsingReflection($identityId, 'uuid');
        $queryString = "SELECT a FROM " . Account::class . " a WHERE a.trader.identityId.uuid = :identityIdUuid";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('identityIdUuid', $identityIdUuid);

        $accounts = $query->getResult();
        return !empty($accounts);
    }

    public function findAccountFor(TraderNumber $traderNumber): ?Account
    {
        $traderNumberValue = ValueObjectPropertyGetter::getValueUsingReflection($traderNumber, 'value');
        $queryString = "SELECT a FROM " . Account::class . " a WHERE a.trader.number.value = :traderNumberValue";
        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('traderNumberValue', $traderNumberValue);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findAllByTraderNumber(TraderNumber $traderNumber): array
    {
        $traderNumberValue = ValueObjectPropertyGetter::getValueUsingReflection($traderNumber, 'value');
        $queryString = "SELECT new App\\Account\\Domain\\WalletData(
                            w.walletId,
                            w.funds.value.value.value,
                            w.funds.value.currency.code
                        )
                        FROM " . Account::class . " a 
                        JOIN a.wallets w 
                        WHERE a.trader.number.value = :traderNumberValue";

        $query = $this->entityManager->createQuery($queryString);
        $query->setParameter('traderNumberValue', $traderNumberValue);

        return $query->getResult();
    }
}
