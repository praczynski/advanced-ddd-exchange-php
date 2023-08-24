<?php

namespace App\Quoting\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Quoting\Domain\Exception\QuoteNotFoundException;
use App\Quoting\Domain\ExchangeDomainService;
use App\Quoting\Domain\MoneyToExchange;
use App\Quoting\Domain\Quote;
use App\Quoting\Domain\QuoteNumber;
use App\Quoting\Domain\QuoteRepository;
use App\Quoting\Domain\Requester;
use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;

class QuoteApplicationService
{
    private LoggerInterface $log;
    private iterable $currencyExchangeRateAdvisors;
    private QuoteRepository $quoteRepository;
    private ExchangeDomainService $exchangeDomainService;

    public function __construct(LoggerInterface $log, iterable $currencyExchangeRateAdvisors, QuoteRepository $quoteRepository, ExchangeDomainService $exchangeDomainService)
    {
        $this->log = $log;
        $this->currencyExchangeRateAdvisors = $currencyExchangeRateAdvisors;
        $this->quoteRepository = $quoteRepository;
        $this->exchangeDomainService = $exchangeDomainService;
    }

    public function prepareQuote(PrepareQuoteCommand $prepareQuoteCommand): PrepareQuoteStatus
    {
        try {

            $currencyToSell = $prepareQuoteCommand->getCurrencyToSell();
            $currencyToBuy = $prepareQuoteCommand->getCurrencyToBuy();
            $requester = new Requester($prepareQuoteCommand->getIdentityId());

            $moneyToExchange = new MoneyToExchange(
                $prepareQuoteCommand->getMoneyToExchangeValue(),
                Currency::fromString($prepareQuoteCommand->getMoneyToExchangeCurrency())
            );

            $quote = $this->quoteRepository->findActiveQuote($requester, $currencyToSell, $currencyToBuy, $moneyToExchange);

            if ($quote !== null) {
                return PrepareQuoteStatus::prepareExistsStatus($quote->getQuoteId());
            }

            $bestExchangeRate = $this->exchangeDomainService->getBestExchangeRate(
                $requester,
                $moneyToExchange,
                $this->currencyExchangeRateAdvisors,
                $currencyToSell,
                $currencyToBuy
            );

            $moneyExchanged = $bestExchangeRate->exchange($moneyToExchange);

            $quoteExpirationDatePolicy = $this->exchangeDomainService->determineQuoteExpirationDatePolicy($requester);

            $preparedQuote = new Quote(
                $requester,
                $bestExchangeRate,
                $moneyToExchange,
                $moneyExchanged,
                $quoteExpirationDatePolicy
            );

            $this->quoteRepository->save($preparedQuote);

            return PrepareQuoteStatus::prepareSuccessStatus($moneyExchanged, $preparedQuote->getQuoteId());
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function acceptQuote(string $quoteId): AcceptQuoteStatus
    {
        try {
            $quoteNumber = QuoteNumber::fromString($quoteId);
            $quote = $this->quoteRepository->getQuote($quoteNumber);

            $quote->accept();
            $this->quoteRepository->save($quote);

            return AcceptQuoteStatus::SUCCESS();
        } catch (QuoteNotFoundException $e) {

            $this->log->error('Quote not found', ['exception' => $e]);
            return AcceptQuoteStatus::QUOTE_NOT_FOUND();
        }
    }

    public function expireQuotes(): void
    {
        $quotes = $this->quoteRepository->findAllQuotesToExpire();

        foreach ($quotes as $quote) {
            $quote->expire();
            $this->quoteRepository->save($quote);
        }
    }

    public function expireAllQuotesForCurrency(Currency $currencyToSell, Currency $currencyToBuy): void {
        $quotes = $this->quoteRepository->findAllQuotesToExpireByCurrency($currencyToSell, $currencyToBuy);

        foreach ($quotes as $quote) {
            $quote->expire();
            $this->quoteRepository->save($quote);
        }
    }

    public function reject(string $quoteId): AcceptQuoteStatus {
        try {
            $quoteNumber = QuoteNumber::fromString($quoteId);
            $quote = $this->quoteRepository->getQuote($quoteNumber);
            $quote->reject();
            $this->quoteRepository->save($quote);
            return AcceptQuoteStatus::SUCCESS();
        } catch (QuoteNotFoundException $e) {
            $this->log->error('Quote not found', ['exception' => $e]);
            return AcceptQuoteStatus::QUOTE_NOT_FOUND();
        }
    }
}
