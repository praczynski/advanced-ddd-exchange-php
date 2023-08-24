<?php

namespace App\Quoting\Domain\Policy;

use App\Quoting\Domain\ExpirationDate;

interface QuoteExpirationDatePolicy {

    public function generateExpirationDate(): ExpirationDate;

}
