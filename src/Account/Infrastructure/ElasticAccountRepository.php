<?php

namespace App\Account\Infrastructure;

use App\Account\Domain\Account;
use App\Account\Domain\AccountNumber;
use App\Account\Domain\AccountRepository;
use App\Account\Domain\TraderNumber;
use App\Kernel\IdentityId;

class ElasticAccountRepository implements AccountRepository
{
    public function find(AccountNumber $accountNumber): ?Account
    {
        return null;
    }

    public function save(Account $account): void
    {
    }

    public function isThereAccountFor(IdentityId $identityId): bool
    {
        return false;
    }

    public function findAccountFor(TraderNumber $traderNumber): ?Account
    {
        return null;
    }

    public function findAllByTraderNumber(TraderNumber $traderNumber): array
    {
        return [];
    }
}
