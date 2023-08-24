<?php

namespace App\Account\Domain;

use App\Account\Domain\Events\AccountActivated;
use App\Account\Domain\Exception\TransactionLimitExceededException;
use App\Account\Domain\Exception\WalletNotFoundException;
use App\Account\Domain\Exception\WalletsLimitExceededException;
use App\Account\Domain\Policy\WithoutTransactionLimitPolicy;
use App\Account\Domain\Policy\WithoutWalletsLimitPolicy;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name:"accounts")]
class Account {

    private int $cardTransactionDailyLimit = 1;

    #[Id, Column( name: "account_number_uuid", type: "account_number")]
    private AccountNumber $accountNumber;

    #[Embedded(class: "Trader")]
    private Trader $trader;

    //#[JoinColumn(name: "account_number_uuid", nullable: false)]
    #[OneToMany(mappedBy: 'account', targetEntity: Wallet::class, cascade: ["ALL"], orphanRemoval: true)]
    private Collection $wallets;

    // #[OneToMany(mappedBy: 'account', targetEntity: Transaction::class, cascade: ["ALL"], orphanRemoval: true)]
    // private Collection $transactions;
    #[Embedded(class: "Status")]
    private Status $status;

    public function __construct(AccountNumber $accountNumber, Trader $trader)
    {
        $this->accountNumber = $accountNumber;
        $this->trader = $trader;
        // $this->transactions = [];
        $this->status = Status::createInactive();
        $this->wallets = new ArrayCollection();
        $this->wallets->add(new Wallet($this, Funds::zeroPLN()));
    }

    public function activateAccount(AccountDomainEventBus $eventsBus): void {
       $this->status = Status::createActive();
        $eventsBus->post(new AccountActivated($this->trader->identity(function($identity) {
            return $identity;
        })));
    }

    public function depositFunds(Funds $funds, TransactionType $transactionType): void
    {

        $transactionLimitPolicy = new WithoutTransactionLimitPolicy();

        if (!$transactionLimitPolicy->withinTheLimit($funds)) {
            throw new TransactionLimitExceededException("Transaction limit exceeded");
        }

        $walletToDeposit = $this->getWalletByCurrency($funds);

        if ($walletToDeposit === null) {

            $walletsLimitPolicy = new WithoutWalletsLimitPolicy();

            if ($walletsLimitPolicy->isWalletsLimitExceeded($this->wallets->count())) {
                throw new WalletsLimitExceededException();
            }

            $this->addWallet(new Wallet($this, $funds));
        } else {
            $walletToDeposit->addFunds($funds);
        }


        // $this->transactions[] = new Transaction($transactionType, $funds);
    }

    public function withdrawFunds(Funds $funds, TransactionType $transactionType): void
    {
        $limitPolicy = new WithoutTransactionLimitPolicy();

        if (!$limitPolicy->withinTheLimit($funds)) {
            throw new TransactionLimitExceededException("Transaction limit exceeded");
        }

        $walletToWithdraw = $this->getWalletByCurrency($funds);

        if (!$walletToWithdraw) {
            throw new WalletNotFoundException();
        }

        $walletToWithdraw->withdrawFunds($funds);
        //$this->transactions[] = new Transaction($transactionType, $funds);
    }

    public function transferFunds(Funds $currencyToBuy, Funds $currencyToSell, TransactionType $transactionType): void
    {
        $fromWallet = $this->getWalletByCurrency($currencyToSell);

        if (!$fromWallet) {
            throw new WalletNotFoundException();
        }

        $toWallet = $this->getWalletByCurrency($currencyToBuy);

        if (!$toWallet) {
            $toWallet = new Wallet($this, $currencyToBuy);
            $this->wallets->add($toWallet);
        }else{
            $toWallet->addFunds($currencyToBuy);
        }

        $fromWallet->withdrawFunds($currencyToSell);

        //$this->transactions[] = new Transaction($transactionType, $currencyToBuy);
    }


    public function accountNumber(): AccountNumber
    {
        return $this->accountNumber;
    }

    public function exhaustedTransactionLimitForToday(TransactionType $transactionType): bool {
        return !$transactionType->equals(TransactionType::CARD()) || $this->countDailyTransactionByType(TransactionType::CARD()) > $this->cardTransactionDailyLimit;
    }

    private function countDailyTransactionByType(TransactionType $transactionType): int {
      /*  $today = new \DateTime();

        return count(array_filter($this->transactions, function ($trans) use ($transactionType, $today) {
            return $trans->type()->equals($transactionType) && $trans->transactionDate()->isItTheSameDay($today);
        }));*/
        return 1;
    }

    private function getWalletByCurrency(Funds $funds): ?Wallet
    {
        $matchingWallets = $this->wallets->filter(fn(Wallet $wallet) => $wallet->isSameCurrency($funds));

        return $matchingWallets->first() ?: null;
    }

    private function addWallet(Wallet $wallet): void
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets->add($wallet);
        }
    }

}
