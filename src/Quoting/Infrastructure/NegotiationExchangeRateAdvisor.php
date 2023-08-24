<?php

namespace App\Quoting\Infrastructure;


use App\Kernel\Currency;
use App\Negotiation\Application\FindAcceptedActiveNegotiationRateCommand;
use App\Negotiation\Application\NegotiationApplicationService;
use App\Quoting\Domain\ExchangeRate;
use App\Quoting\Domain\ExchangeRateAdvisor;
use App\Quoting\Domain\MoneyToExchange;
use App\Quoting\Domain\Rate;
use App\Quoting\Domain\Requester;

class NegotiationExchangeRateAdvisor implements ExchangeRateAdvisor {

    private NegotiationApplicationService $negotiationApplicationService;


    public function __construct(NegotiationApplicationService $negotiationApplicationService) {
        $this->negotiationApplicationService = $negotiationApplicationService;
    }

    public function exchangeRate(Requester $requester, MoneyToExchange $moneyToExchange, Currency $currencyToSell, Currency $currencyToBuy): ?ExchangeRate {

        $findAcceptedActiveNegotiationRateCommand = new FindAcceptedActiveNegotiationRateCommand(
            $requester->identityId(),
            $currencyToSell,
            $currencyToBuy,
            $moneyToExchange->value(function($x) {
                return $x;
            }),
            Currency::fromString($moneyToExchange->currency(function($x) {
                return $x;
            }))
        );

        $negotiationRateResponse = $this->negotiationApplicationService->findAcceptedActiveNegotiationRate($findAcceptedActiveNegotiationRateCommand);

        if ($negotiationRateResponse->getStatus() === "SUCCESS") {
            return ExchangeRate::create($currencyToSell, $currencyToBuy, Rate::fromString($negotiationRateResponse->getRate()));
        }

        return null;
    }
}