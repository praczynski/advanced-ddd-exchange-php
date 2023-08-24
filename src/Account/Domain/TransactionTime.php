<?php

namespace App\Account\Domain;

#[Embeddable]
class TransactionTime {
    private \DateTime $transactionTime;

    function __construct(\DateTime $transactionTime){
        $this->transactionTime = $transactionTime;
    }

    function isItTheSameDay(\DateTime $date): bool {
        return $this->transactionTime->format('Y-m-d') === $date->format('Y-m-d');
    }
}
