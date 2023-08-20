<?php

namespace App\Currency;

use App\Kernel\BigDecimal\BigDecimal;

class CurrencyPairResponse {


    public function getStatus(): string {
        return "SUCESS";
    }


    public function getExchangeRate(): BigDecimal {
        //TODO
        return new BigDecimal("100");
    }
}