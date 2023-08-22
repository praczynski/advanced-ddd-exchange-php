<?php

namespace App\Account\Application;

use App\Account\Domain\Account;
use App\Account\Domain\AccountFactory;
use App\Account\Domain\AccountNumber;
use App\Account\Domain\AccountRepository;
use App\Account\Domain\Exception\AccountNotFoundException;
use App\Account\Domain\Exception\InsufficientFundsException;
use App\Account\Domain\Exception\TransactionLimitExceededException;
use App\Account\Domain\Exception\WalletsLimitExceededException;
use App\Account\Domain\ExchangeRate;
use App\Account\Domain\Funds;
use App\Account\Domain\TraderNumber;
use App\Account\Domain\TransactionType;
use App\Account\Domain\TransferFundsDomainService;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;
use App\Kernel\Money;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class AccountApplicationService
{

    private LoggerInterface $log;
    private AccountRepository $accountRepository;
    private AccountFactory $accountFactory;

    public function __construct(AccountRepository $dbAccountRepository, AccountFactory $accountFactory, LoggerInterface $log)
    {
        $this->accountRepository = $dbAccountRepository;
        $this->accountFactory = $accountFactory;
        $this->log = $log;
    }

    public function createAccount(IdentityId $identityId): CreateAccountStatus
    {
        if (!$identityId) {
            throw new \InvalidArgumentException("identityId cannot be null");
        }

        $accountStatus = $this->accountFactory->createAccount($identityId);
        if ($accountStatus->isSuccess()) {
            $this->accountRepository->save($accountStatus->account());

           return CreateAccountStatus::success(
                $accountStatus->status(),
                $identityId,
                $accountStatus->accountNumber(),
                $accountStatus->traderNumber());
        }
        return CreateAccountStatus::createFailAccountStatus($accountStatus->status(), $identityId);
    }

    public function activateAccount(string $accountId): ActivateAccountStatus
    {
        $accountNumber = AccountNumber::fromString($accountId);
        $account = $this->accountRepository->find($accountNumber);

        if ($account !== null) {
            $account->activateAccount();
            $this->accountRepository->save($account);
            return ActivateAccountStatus::successStatus();
        } else {
            return ActivateAccountStatus::failStatus();
        }
    }

    public function depositFundsByCard(DepositFundsByCardCommand $command): DepositFundsStatus
    {
        $account = $this->accountRepository->findAccountFor(TraderNumber::fromString($command->getTraderNumber()));

        if (!$account) {
            $this->log->error("Account Not Found");
            return DepositFundsStatus::ACCOUNT_NOT_FOUND();
        }

        try {
            $this->depositFund($account, $command->getFundsToDeposit(), TransactionType::CARD(), $command->getCurrency());
            return DepositFundsStatus::success($account->accountNumber()->toString());
        } catch (WalletsLimitExceededException $e) {
            $this->log->error("Wallets Limit Exceeded", ['exception' => $e]);
            return DepositFundsStatus::WALLETS_LIMIT_EXCEEDED();
        } catch (TransactionLimitExceededException $e) {
            $this->log->error("Transaction Limit Exceeded", ['exception' => $e]);
            return DepositFundsStatus::TRANSACTION_LIMIT_EXCEEDED();
        }
    }

    public function depositFunds(DepositFundCommand $command): DepositFundsStatus
    {

        $account = $this->accountRepository->find(AccountNumber::fromString($command->getAccountNumber()));

        if (!$account) {
            $this->log->error("Account Not Found");
            return DepositFundsStatus::ACCOUNT_NOT_FOUND();
        }

        try {

            $this->depositFund($account, $command->getFundsToDeposit(), TransactionType::CARD(), $command->getCurrency());
            return DepositFundsStatus::success($account->accountNumber()->toString());
        } catch (WalletsLimitExceededException $e) {

            $this->log->error("Wallets Limit Exceeded", ['exception' => $e]);
            return DepositFundsStatus::WALLETS_LIMIT_EXCEEDED();
        } catch (TransactionLimitExceededException $e) {

            $this->log->error("Transaction Limit Exceeded", ['exception' => $e]);
            return DepositFundsStatus::TRANSACTION_LIMIT_EXCEEDED();
        }
    }

    public function buyCurrency(string $traderNumber, Money $currencyToBuy, ExchangeRateCommand $command): BuyCurrencyStatus {

        $exchangeRate = new ExchangeRate(
            Currency::fromString($command->getCurrencyToSell()),
            Currency::fromString($command->getCurrencyToBuy()),
            $command->getRate()
        );

        try {
            $account = $this->accountRepository->findAccountFor(new TraderNumber($traderNumber));

            if (!$account) {
                throw new AccountNotFoundException("Account not found");
            }

            $account->exchangeCurrency(Funds::fromMoney($currencyToBuy), $exchangeRate, TransactionType::CURRENCY_EXCHANGE());
            $this->accountRepository->save($account);

            return BuyCurrencyStatus::BUY_SUCCESS();
        } catch (AccountNotFoundException $e) {
            $this->log->error("Account Not Found", ['exception' => $e]);
            return BuyCurrencyStatus::ACCOUNT_NOT_FOUND();
        } catch (InsufficientFundsException $e) {
            $this->log->error("Insufficient funds", ['exception' => $e]);
            return BuyCurrencyStatus::INSUFFICIENT_FUNDS();
        }
    }

    public function withdrawFunds(WithdrawFundsCommand $command): WithdrawStatus
    {
        $account = $this->accountRepository->findAccountFor(TraderNumber::fromString($command->getTraderNumber()));
        if (!$account) {
            $this->log->error("Account Not Found");
            return WithdrawStatus::ACCOUNT_NOT_FOUND();
        }

        try {

            $account->withdrawFunds(Funds::fromValueAndCurrency($command->getFundsToWithdraw(), $command->getCurrency()), TransactionType::CARD());
            $this->accountRepository->save($account);
            return WithdrawStatus::WITHDRAW_SUCCESS();
        } catch (InsufficientFundsException $e) {

            $this->log->error("Insufficient funds", ['exception' => $e]);
            return WithdrawStatus::INSUFFICIENT_FUNDS();
        } catch (TransactionLimitExceededException $e) {

            $this->log->error("Transaction Limit Exceeded", ['exception' => $e]);
            return WithdrawStatus::TRANSACTION_LIMIT_EXCEEDED();
        }
    }

    public function transferFundsBetweenAccount(TransferFundsBetweenAccountCommand $command): TransferFundsStatus
    {
        /*
        try {
            //TODO
            return TransferFundsStatus::TRANSFER_SUCCESS();
        } catch (InsufficientFundsException $e) {

            $this->log->error("Insufficient funds", ['exception' => $e]);
            return TransferFundsStatus::INSUFFICIENT_FUNDS();
        } catch (TransactionLimitExceededException $e) {

            $this->log->error("Transaction Limit Exceeded", ['exception' => $e]);
            return TransferFundsStatus::TRANSACTION_LIMIT_EXCEEDED();
        } catch (WalletsLimitExceededException $e) {
            $this->log->error("Wallets Limit Exceeded", ['exception' => $e]);
            return TransferFundsStatus::WALLETS_LIMIT_EXCEEDED();
        }*/
        return TransferFundsStatus::TRANSFER_SUCCESS();
    }

    public function getAllWalletsForTrader(string $traderNumber): array
    {
        return $this->accountRepository->findAllByTraderNumber(TraderNumber::fromString($traderNumber));
    }

    /**
     * @throws TransactionLimitExceededException
     * @throws WalletsLimitExceededException
     */
    private function depositFund(Account $account, BigDecimal $fundsToDeposit, TransactionType $transactionType, Currency $currency): void
    {
        $account->depositFunds(Funds::fromValueAndCurrency($fundsToDeposit, $currency), $transactionType);
        $this->accountRepository->save($account);
    }

}
