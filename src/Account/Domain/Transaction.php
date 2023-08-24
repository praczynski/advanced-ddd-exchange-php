<?php

namespace App\Account\Domain;


use DateTime;

#[Entity]
#[Table(name:"transactions")]
class Transaction {

    #[Id]
	#[Embedded(class: "TransactionNumber")]
    private TransactionNumber $number;

	#[Embedded(class: "TransactionType")]
    private TransactionType $type;

	#[Embedded(class: "Funds")]
    private Funds $value;

	#[Embedded(class: "TransactionTime")]
    private TransactionTime $date;

    public function __construct(TransactionType $type, Funds $value) {
        $this->number = TransactionNumber::generate();
        $this->type = $type;
        $this->value = $value;
        $this->date = new TransactionTime(new DateTime()); //
    }

    public function transactionDate(): TransactionTime {
        return $this->date;
    }

    public function type(): TransactionType {
        return $this->type;
    }
}
