<?php

namespace App\Account\Domain;

use App\Kernel\IdentityId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name:"wallets")]
class Wallet {

    #[Id, Column( name: "wallet_id_uuid", type: "wallet_id")]
    private WalletId $walletId;

	#[Embedded(class: "Funds")]
    private Funds $funds;

    //#[ManyToOne(targetEntity: "Account")]
    //#[JoinColumn(name: "account_number_uuid", referencedColumnName: "account_number_uuid", nullable: false)]

    #[ManyToOne(targetEntity: Account::class, inversedBy: 'wallets')]
    #[JoinColumn(name: 'account_number_uuid', referencedColumnName: 'account_number_uuid', nullable: false)]
    private Account $account;

    public function __construct(Account $account, Funds $funds){
        $this->walletId = WalletId::generate();
        $this->account = $account;
        $this->funds = $funds;
    }

    public function isSameCurrency(Funds $funds): bool {
        return $this->funds->isSameCurrencyFunds($funds);
    }

    public function addFunds(Funds $funds): void {
        $this->funds = $this->funds->addFunds($funds);
    }

    public function withdrawFunds(Funds $funds): void
    {
        $this->funds = $this->funds->withdrawFunds($funds);
    }

    public function getWalletId(): WalletId
    {
        return $this->walletId;
    }
}
