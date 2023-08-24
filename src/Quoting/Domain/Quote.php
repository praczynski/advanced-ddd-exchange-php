<?php

namespace App\Quoting\Domain;

use App\Quoting\Domain\Policy\QuoteExpirationDatePolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity]
#[Table(name:"quotes")]
class Quote
{
    //cannot create primary key for embedded object
    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: QuoteNumber::class)]
    private QuoteNumber $quoteNumber;

    #[Embedded(class: Requester::class)]
    private Requester $requester;

    #[Embedded(class: ExpirationDate::class)]
    private ExpirationDate $expirationDate;

    #[Embedded(class: BestExchangeRate::class)]
    private BestExchangeRate $bestExchangeRate;

    #[Embedded(class: MoneyToExchange::class)]
    private MoneyToExchange $moneyToExchange;

    #[Embedded(class: MoneyExchanged::class)]
    private MoneyExchanged $moneyExchanged;

    #[Embedded(class: QuoteStatus::class)]
    private QuoteStatus $quoteStatus;

    public function __construct(
        Requester $requester,
        BestExchangeRate $bestExchangeRate,
        MoneyToExchange $moneyToExchange,
        MoneyExchanged $moneyExchanged,
        QuoteExpirationDatePolicy $quoteExpirationDatePolicy
    ) {
        $this->quoteNumber = QuoteNumber::generate();
        $this->requester = $requester;
        $this->bestExchangeRate = $bestExchangeRate;
        $this->moneyToExchange = $moneyToExchange;
        $this->moneyExchanged = $moneyExchanged;
        $this->quoteStatus = QuoteStatus::PREPARED();
        $this->expirationDate = $quoteExpirationDatePolicy->generateExpirationDate();
    }

    public function accept(): void
    {
        if ($this->quoteStatus != QuoteStatus::EXPIRED() && $this->quoteStatus != QuoteStatus::REJECTED()) {
            $this->quoteStatus = QuoteStatus::ACCEPTED();
        }
    }

    public function reject(): void
    {
        if ($this->quoteStatus != QuoteStatus::EXPIRED() && $this->quoteStatus != QuoteStatus::ACCEPTED()) {
            $this->quoteStatus = QuoteStatus::REJECTED();
        }
    }

    public function expire(): void
    {
        if ($this->quoteStatus != QuoteStatus::ACCEPTED() && $this->quoteStatus != QuoteStatus::REJECTED()) {
            $this->quoteStatus = QuoteStatus::EXPIRED();
        }
    }

    public function getQuoteId(): QuoteNumber
    {
        return $this->quoteNumber;
    }
}
