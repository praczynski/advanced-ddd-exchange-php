<?php

namespace App\Account\Domain;

use App\Account\Domain\Exception\TransactionLimitExceededException;
use App\Account\Domain\Exception\WalletsLimitExceededException;

class TransferFundsDomainService {

    /**
     * @throws TransactionLimitExceededException
     * @throws WalletsLimitExceededException
     */
    public function transferFunds(Account $accountFrom, Account $accountTo, Funds $funds): void
    {
        $accountFrom->withdrawFunds($funds, TransactionType::TRANSFER_BETWEEN_ACCOUNTS_WITHDRAW());
        $accountTo->depositFunds($funds, TransactionType::TRANSFER_BETWEEN_ACCOUNTS_DEPOSIT());
    }
}