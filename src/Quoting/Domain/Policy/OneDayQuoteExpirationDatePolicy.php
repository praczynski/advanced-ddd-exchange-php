<?php

namespace App\Quoting\Domain\Policy;

use App\Quoting\Domain\ExpirationDate;

class OneDayQuoteExpirationDatePolicy implements QuoteExpirationDatePolicy {

    public function generateExpirationDate(): ExpirationDate {
        return ExpirationDate::oneDayExpirationDate();
    }
}
