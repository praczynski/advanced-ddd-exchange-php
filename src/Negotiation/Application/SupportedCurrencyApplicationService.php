<?php

namespace App\Negotiation\Application;



use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Negotiation\Domain\Supportedcurrency\Rate;
use App\Negotiation\Domain\Supportedcurrency\SupportedCurrency;
use App\Negotiation\Domain\Supportedcurrency\SupportedCurrencyRepository;
use Exception;


class SupportedCurrencyApplicationService
{

    private SupportedCurrencyRepository $repository;

    public function __construct(SupportedCurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addSupportedCurrency(Currency $baseCurrency, Currency $targetCurrency, BigDecimal $rate): void
    {
        $supportedCurrency = new SupportedCurrency($baseCurrency, $targetCurrency, new Rate($rate));
        $this->repository->save($supportedCurrency);
    }

    public function adjustCurrencyPair(Currency $baseCurrency, Currency $targetCurrency, BigDecimal $rate): void
    {
        $supportedCurrency = $this->findSupportedCurrency($baseCurrency, $targetCurrency);
        $supportedCurrency->setRate(new Rate($rate));
        $this->repository->save($supportedCurrency);
    }

    public function activateCurrencyPair(Currency $baseCurrency, Currency $targetCurrency): void {
        $supportedCurrency = $this->findSupportedCurrency($baseCurrency, $targetCurrency);
        $supportedCurrency->activate();
        $this->repository->save($supportedCurrency);
    }

    public function deactivateCurrencyPair(Currency $baseCurrency, Currency $targetCurrency): void {
        $supportedCurrency = $this->findSupportedCurrency($baseCurrency, $targetCurrency);
        $supportedCurrency->deactivate();
        $this->repository->save($supportedCurrency);
    }

    private function findSupportedCurrency(Currency $baseCurrency, Currency $targetCurrency): SupportedCurrency {
        $optionalSupportedCurrency = $this->repository->findByCurrency($baseCurrency, $targetCurrency);

        if ($optionalSupportedCurrency === null) {
            throw new Exception("SupportedCurrency not found");
        }

        return $optionalSupportedCurrency;
    }
}
