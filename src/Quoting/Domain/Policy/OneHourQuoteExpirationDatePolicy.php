<?php

namespace App\Quoting\Domain\Policy;

use App\Quoting\Domain\ExpirationDate;

class OneHourQuoteExpirationDatePolicy implements QuoteExpirationDatePolicy {

    public function generateExpirationDate(): ExpirationDate {
        return ExpirationDate::oneHourExpirationDate();
    }
}
