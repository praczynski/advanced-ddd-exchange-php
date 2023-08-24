<?php

namespace App\Account\Domain;

use App\Kernel\IdentityId;

class AccountFactory {

    private AccountRepository $dbAccountRepository;

    public function __construct(AccountRepository $dbAccountRepository) {
        $this->dbAccountRepository = $dbAccountRepository;
    }

    public function createAccount(IdentityId $identityId): AccountStatus {
        if ($this->dbAccountRepository->isThereAccountFor($identityId)) {
            return AccountStatus::createAccountAlreadyExistsStatus();
        }

        $accountNumber = AccountNumber::generate();
        $traderNumber = TraderNumber::generateNewNumber();
        $trader = new Trader($traderNumber, $identityId);
        $account = new Account($accountNumber, $trader);

        return AccountStatus::createSuccessAccountStatus(AccountStatus::SUCCESS, $account, $accountNumber->toString(), $traderNumber->toString());
    }
}

