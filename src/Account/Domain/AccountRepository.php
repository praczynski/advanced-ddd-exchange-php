<?php

namespace App\Account\Domain;

use App\Kernel\IdentityId;

interface AccountRepository {

    public function find(AccountNumber $accountNumber): ?Account;

    public function save(Account $account): void;

    public function isThereAccountFor(IdentityId $identityId): bool;

    public function findAccountFor(TraderNumber $traderNumber): ?Account;

    public function findAllByTraderNumber(TraderNumber $traderNumber): array;

}
