<?php

namespace App\Negotiation\Application;

use App\Kernel\BigDecimal\BigDecimal;
use App\Negotiation\Domain\Event\NegotiationApproved;
use App\Negotiation\Domain\Event\NegotiationCreated;
use App\Negotiation\Domain\Exception\NegotiationNotFoundException;
use App\Negotiation\Domain\Negotiation;
use App\Negotiation\Domain\NegotiationAcceptanceService;
use App\Negotiation\Domain\NegotiationId;
use App\Negotiation\Domain\NegotiationRate;
use App\Negotiation\Domain\NegotiationRepository;
use App\Negotiation\Domain\Negotiator;
use App\Negotiation\Domain\OperatorId;
use App\Negotiation\Domain\ProposedExchangeAmount;
use App\Negotiation\Domain\Supportedcurrency\SupportedCurrencyRepository;
use Exception;
use Psr\Log\LoggerInterface;

class NegotiationApplicationService
{
    private LoggerInterface $LOG;

    private NegotiationRepository $negotiationRepository;
    private ManualNegotiationApproveNotifier $manualNegotiationApproveNotifier;
    private iterable $negotiationAmountAutomaticApprovePolicies;
    private BaseExchangeRateAdvisor $baseExchangeRateAdvisor;
    private NegotiationAcceptanceService $negotiationAcceptanceService;
    private SupportedCurrencyRepository $supportedCurrencyRepository;
    private iterable $eventBuses;

    public function __construct(
        NegotiationRepository $negotiationRepository,
        ManualNegotiationApproveNotifier $manualNegotiationApproveNotifier,
        iterable $negotiationAmountAutomaticApprovePolicies,
        BaseExchangeRateAdvisor $baseExchangeRateAdvisor,
        NegotiationAcceptanceService $negotiationAcceptanceService,
        SupportedCurrencyRepository $supportedCurrencyRepository,
        iterable $eventBuses,
        LoggerInterface $LOG
    ) {
        $this->negotiationRepository = $negotiationRepository;
        $this->manualNegotiationApproveNotifier = $manualNegotiationApproveNotifier;
        $this->negotiationAmountAutomaticApprovePolicies = $negotiationAmountAutomaticApprovePolicies;
        $this->baseExchangeRateAdvisor = $baseExchangeRateAdvisor;
        $this->negotiationAcceptanceService = $negotiationAcceptanceService;
        $this->supportedCurrencyRepository = $supportedCurrencyRepository;
        $this->eventBuses = $eventBuses;
        $this->LOG = $LOG;
    }

    public function createNegotiation(CreateNegotiationCommand $command): CreateNegotiationStatus
    {
        $this->negotiationRepository->beginTransaction();

        try {
            $negotiator = Negotiator::fromIdentity($command->getIdentityId());
            $proposedExchangeAmount = ProposedExchangeAmount::fromValueAndCurrency(
                $command->getProposedExchangeAmount(),
                $command->getProposedExchangeCurrency());

            if ($this->negotiationRepository->alreadyExistsActiveNegotiationForNegotiator(
                $negotiator,
                $command->getBaseCurrency(),
                $command->getTargetCurrency(),
                $command->getProposedRate(),
                $proposedExchangeAmount
            )) {
                return CreateNegotiationStatus::ALREADY_EXISTS();
            }

            $activeCurrency = $this->supportedCurrencyRepository->findActiveByCurrency($command->getBaseCurrency(), $command->getTargetCurrency());

            if (!$activeCurrency) {
                return CreateNegotiationStatus::CURRENCY_PAIR_NOT_SUPPORTED();
           }

            $baseExchangeRate = new BigDecimal("23");

            $negotiation = new Negotiation(
                $negotiator,
                $proposedExchangeAmount,
                $command->getBaseCurrency(),
                $command->getTargetCurrency(),
                new NegotiationRate($command->getProposedRate(), $baseExchangeRate)
            );

            $status = $negotiation->tryAutomaticApprove($this->negotiationAmountAutomaticApprovePolicies);

            $this->negotiationRepository->save($negotiation);

            if ($status->isApproved()) {
                // $this->negotiationAcceptanceService->negotiationAccepted($negotiation);
                foreach ($this->eventBuses as $eventBus) {
                    $eventBus->postNegotiationApproved(new NegotiationApproved($negotiation->negotiationId(), $negotiation->negotiator(), $negotiation->proposedExchangeAmount()->asMoney()));
                }
            } else {
                $this->manualNegotiationApproveNotifier->notifyManualApprovalRequired();
            }

            foreach ($this->eventBuses as $eventBus) {
                $eventBus->postNegotiationCreated(new NegotiationCreated($negotiation->negotiationId(), $negotiation->negotiator(), $negotiation->proposedExchangeAmount()->asMoney()));
            }

            $this->negotiationRepository->commit();

            return $status->isApproved() ? CreateNegotiationStatus::APPROVED() : CreateNegotiationStatus::PENDING();
        } catch (Exception $e) {
            $this->negotiationRepository->rollback();
            throw $e;
        }
    }

    public function approveNegotiation(string $negotiationId, string $operatorId): void
    {
        try {

            $negotiation = $this->negotiationRepository->findById(NegotiationId::fromString($negotiationId));

            $negotiation->approve(OperatorId::fromString($operatorId), $this ->eventBuses);

            $this->negotiationRepository->save($negotiation);

            $this->manualNegotiationApproveNotifier->notifyNegotiationApproved($negotiationId);

            // $this->negotiationAcceptanceService->negotiationAccepted($negotiation);

        } catch (NegotiationNotFoundException $e) {
            $this->LOG->error('Negotiation not found', ['exception' => $e]);
        }
    }

    public function rejectNegotiation(string $negotiationId, string $operatorId): void
    {
        try {

            $negotiation = $this->negotiationRepository->findById(NegotiationId::fromString($negotiationId));

            $negotiation->reject(OperatorId::fromString($operatorId));

            $this->negotiationRepository->save($negotiation);

            $this->manualNegotiationApproveNotifier->notifyNegotiationRejected($negotiationId);

        } catch (NegotiationNotFoundException $e) {
            $this->LOG->error('Negotiation not found', ['exception' => $e]);
        }
    }

    public function getNegotiationRateIfApproved(string $negotiationId): NegotiationRateResponse
    {
        //TODO QueryStack
        try {
            return new NegotiationRateResponse($this->negotiationRepository->findApprovedRateById(NegotiationId::fromString($negotiationId)));
        } catch (Exception $e) {
            $this->LOG->error('Negotiation not found', ['exception' => $e]);
            return NegotiationRateResponse::failed();
        }
    }

    public function findAcceptedActiveNegotiationRate(FindAcceptedActiveNegotiationRateCommand $command): NegotiationRateResponse
    {
        $proposedExchangeAmount = ProposedExchangeAmount::fromValueAndCurrency($command->getProposedExchangeAmount(), $command->getProposedExchangeCurrency());
        $acceptedActiveNegotiation = $this->negotiationRepository->findAcceptedActiveNegotiation(
            Negotiator::fromIdentity($command->getIdentityId()),
            $command->getBaseCurrency(),
            $command->getTargetCurrency(),
            $proposedExchangeAmount
        );

        if ($acceptedActiveNegotiation !== null) {
            return new NegotiationRateResponse($acceptedActiveNegotiation);
        } else {
            return NegotiationRateResponse::failed();
        }
    }
}
