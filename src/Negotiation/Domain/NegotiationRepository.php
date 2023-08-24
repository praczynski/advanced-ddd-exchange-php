<?php

namespace App\Negotiation\Domain;


use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;

interface NegotiationRepository {
    function save(Negotiation $negotiation);

    function findById(NegotiationId $id): ?Negotiation;
    function alreadyExistsActiveNegotiationForNegotiator(Negotiator $negotiator, Currency $baseCurrency, Currency $targetCurrency, BigDecimal $rate, ProposedExchangeAmount $proposedExchangeAmount): bool;
    function findApprovedRateById(NegotiationId $negotiationId): ?BigDecimal;
    function findAcceptedActiveNegotiation(Negotiator $negotiator, Currency $baseCurrency, Currency $targetCurrency, ProposedExchangeAmount $proposedExchangeAmount): ?BigDecimal;
    function beginTransaction(): void;
    function commit(): void;
    function rollback(): void;
}