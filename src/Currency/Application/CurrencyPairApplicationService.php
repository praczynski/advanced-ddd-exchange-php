<?php

namespace App\Currency\Application;

use App\Currency\Domain\CurrencyPairId;
use App\Currency\Domain\Exception\CurrencyPairNotSupportedException;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use Exception;
use Psr\Log\LoggerInterface;
use App\Currency\Domain\CurrencyPairRepository;
use App\Currency\Domain\CurrencyPairFactory;

use Doctrine\ORM\EntityManagerInterface;

class CurrencyPairApplicationService
{
    private LoggerInterface $logger;
    private CurrencyPairRepository $repository;
    private CurrencyPairFactory $factory;

    public function __construct(LoggerInterface $logger, CurrencyPairRepository $repository, CurrencyPairFactory $factory)
    {
        $this->logger = $logger;
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function addCurrencyPair(Currency $baseCurrency, Currency $targetCurrency): AddCurrencyPairStatus {
        if ($this->repository->alreadyExists($baseCurrency, $targetCurrency)) {
            return AddCurrencyPairStatus::createCurrencyPairAlreadyExistsStatus();
        }

        try {
            $currencyPair = $this->factory->create($baseCurrency, $targetCurrency);
            $this->repository->save($currencyPair);
            return AddCurrencyPairStatus::createSuccessStatus($currencyPair->currencyPairId()->toString());
        } catch (CurrencyPairNotSupportedException $e) {
            $this->logger->error("Currency pair not supported: " . $baseCurrency . " -> " . $targetCurrency);
            return AddCurrencyPairStatus::createFailureStatus("Currency pair not supported: " . $baseCurrency . " -> " . $targetCurrency);
        }
    }

    public function addCurrencyPairWithRate(BigDecimal $rate, Currency $baseCurrency, Currency $targetCurrency): AddCurrencyPairWithRateResponse
    {

        try {
            if ($this->repository->alreadyExists($baseCurrency, $targetCurrency)) {
                return AddCurrencyPairWithRateResponse::createAlreadyExistsStatus();
            }

            $currencyPair = $this->factory->createWithAdjustedRate($rate, $baseCurrency, $targetCurrency);

            $this->repository->save($currencyPair);

            return AddCurrencyPairWithRateResponse::createSuccessStatus();
        } catch (CurrencyPairNotSupportedException $e) {
            $this->logger->error("Currency pair not supported: " . $baseCurrency . " -> " . $targetCurrency);

            return AddCurrencyPairWithRateResponse::createNorSupportedStatus();
        }
    }

    public function deactivateCurrencyPair(string $currencyPairId): DeactivateCurrencyPairStatus
    {

        $existingCurrencyPair = $this->repository->findById(CurrencyPairId::fromString($currencyPairId));

        if (null === $existingCurrencyPair) {
            return DeactivateCurrencyPairStatus::createCurrencyPairNotFoundStatus();
        }

        try {
            $existingCurrencyPair->deactivate();
            $this->repository->save($existingCurrencyPair);
            return DeactivateCurrencyPairStatus::createSuccessStatus();
        } catch (Exception $e) {
            return DeactivateCurrencyPairStatus::createCurrencyPairNotFoundStatus();
        }
    }

    public function updateCurrencyPairRate(Currency $baseCurrency, Currency $targetCurrency, BigDecimal $adjustedRate): UpdateCurrencyPairRateStatus
    {

        $existingCurrencyPair = $this->repository->findByBaseCurrencyAndTargetCurrency($baseCurrency, $targetCurrency);

        if (null === $existingCurrencyPair) {
            return UpdateCurrencyPairRateStatus::createCurrencyPairNotFoundStatus();
        }

        try {
            $existingCurrencyPair->adjustExchangeRate($adjustedRate);

            $this->repository->save($existingCurrencyPair);

            return UpdateCurrencyPairRateStatus::createSuccessStatus();
        } catch (Exception $e) {
            print_r($e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
            return UpdateCurrencyPairRateStatus::createCurrencyPairNotFoundStatus();
        }
    }

    public function getAllCurrencyPairs(): array
    {
        $currencyPairs = $this->repository->findAll();
        $currencyPairResponses = [];
        foreach ($currencyPairs as $pair) {
            $currencyPairResponses[] = new CurrencyPairResponse(
                $pair->getCurrencyPairId()->toString(),
                $pair->getBaseCurrency(),
                $pair->getTargetCurrency(),
                $pair->getBaseExchangeRate(),
                $pair->getAdjustedExchangeRate()
            );
        }

        return $currencyPairResponses;
    }

    public function getCurrencyPair(Currency $baseCurrency, Currency $targetCurrency): ?CurrencyPairResponse
    {
        $currencyPairData = $this->repository->findDataByBaseCurrencyAndTargetCurrency($baseCurrency, $targetCurrency);

        if ($currencyPairData) {
            return new CurrencyPairResponse(
                $currencyPairData->getCurrencyPairId(),
                $currencyPairData->getBaseCurrency(),
                $currencyPairData->getTargetCurrency(),
                $currencyPairData->getBaseExchangeRate(),
                $currencyPairData->getAdjustedExchangeRate()
            );
        }

        return CurrencyPairResponse::failure();
    }

}

