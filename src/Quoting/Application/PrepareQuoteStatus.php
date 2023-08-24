<?php

namespace App\Quoting\Application;

use App\Quoting\Domain\MoneyExchanged;
use App\Quoting\Domain\QuoteNumber;

class PrepareQuoteStatus
{
    private const QUOTE_EXISTS = "QUOTE_EXISTS";
    private const QUOTE_EXPIRED = "QUOTE_EXPIRED";
    private const QUOTE_PREPARED = "QUOTE_PREPARED";

    private string $status;
    private ?string $quoteNumber = null;
    private ?string $moneyExchanged = null;

    private function __construct(string $status, ?MoneyExchanged $moneyExchanged = null, ?QuoteNumber $quoteNumber = null)
    {
        $this->status = $status;
        $this->moneyExchanged = $moneyExchanged?->toString();
        $this->quoteNumber = $quoteNumber?->toString();
    }

    public static function prepareSuccessStatus(MoneyExchanged $moneyToExchange, QuoteNumber $quoteNumber): self
    {
        return new self(self::QUOTE_PREPARED, $moneyToExchange, $quoteNumber);
    }

    public static function prepareExistsStatus(QuoteNumber $quoteNumber): self
    {
        return new self(self::QUOTE_EXISTS, null, $quoteNumber);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getQuotePrice(): ?string
    {
        return $this->moneyExchanged;
    }

    public function getQuoteId(): ?string
    {
        return $this->quoteNumber;
    }
}
