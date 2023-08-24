<?php

namespace App\Account\Ui;

use App\Account\Application\AccountApplicationService;
use App\Account\Application\DepositFundCommand;
use App\Account\Application\DepositFundsByCardCommand;
use App\Account\Application\TransferFundsBetweenAccountCommand;
use App\Account\Application\WithdrawFundsCommand;
use App\Account\Domain\WalletData;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;
use App\Kernel\Money;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AccountController extends AbstractController
{
    private AccountApplicationService $accountService;
    private LoggerInterface $logger;
    private SerializerInterface $serializer;

    public function __construct(AccountApplicationService $accountService, LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->accountService = $accountService;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/accounts/create/{identityId}", name="account_create", methods={"POST"})
     *
     * @Operation(
     *     tags={"Account"},
     *     summary="Creates a new account",
     *
     *     @OA\Parameter(
     *         name="identityId",
     *         in="path",
     *         description="Identity ID to create an account for",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Account created",
     *     )
     * )
     */
    public function createAccount(string $identityId): Response
    {
        $status = $this->accountService->createAccount(IdentityId::fromString($identityId));

        $jsonString = $this->serializer->serialize($status, 'json');
        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/accounts/deposit/card/{traderNumber}", name="deposit_funds_by_card", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Account"},
     *     summary="Deposits funds by card",
     *
     *     @OA\RequestBody(
     *         description="Funds to deposit",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=FundsToDeposit::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funds deposited",
     *     )
     * )
     */
    public function depositFundsByCard(string $traderNumber, Request $request): Response
    {
        try {
            $fundsToDeposit = $this->serializer->deserialize($request->getContent(), FundsToDeposit::class, 'json');

            $command = new DepositFundsByCardCommand($traderNumber, BigDecimal::fromString($fundsToDeposit->getValue()), new Currency($fundsToDeposit->getCurrency()));
            $status = $this->accountService->depositFundsByCard($command);

            $jsonString = $this->serializer->serialize($status, 'json');
            return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        } catch (RuntimeException $e) {
            $this->logger->error("Undefined Exception", ['exception' => $e]);
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * @Route("/accounts/deposit/{accountId}", name="deposit_funds", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Account"},
     *     summary="Deposits funds",
     *
     *     @OA\RequestBody(
     *         description="Funds to deposit",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=FundsToDeposit::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funds deposited",
     *     )
     * )
     */
    public function depositFunds(string $accountId, Request $request): Response
    {
        try {
            $fundsToDeposit = $this->serializer->deserialize($request->getContent(), FundsToDeposit::class, 'json');

            $depositFundCommand = new DepositFundCommand($accountId, BigDecimal::fromString($fundsToDeposit->getValue()), new Currency($fundsToDeposit->getCurrency()));
            $status = $this->accountService->depositFunds($depositFundCommand);

            $jsonString = $this->serializer->serialize($status, 'json');
            return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        } catch (RuntimeException $e) {
            $this->logger->error("Undefined Exception", ['exception' => $e]);
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * @Route("/accounts/transferFundsBetweenWallets/{traderNumber}", name="transfer_funds_between_wallets", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Account"},
     *     summary="Transfers funds between wallets",
     *
     *     @OA\RequestBody(
     *         description="Funds transfer data",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=TransferFundsBetweenWalletsRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funds transferred",
     *     )
     * )
     */
    public function transferFundsBetweenWallets(string $traderNumber, Request $request): Response
    {
        try {
            $transferRequest = $this->serializer->deserialize($request->getContent(), TransferFundsBetweenWalletsRequest::class, 'json');

            $moneyToBuy = new Money(BigDecimal::fromString($transferRequest->getMoneyToBuyValue()), new Currency($transferRequest->getMoneyToBuyCurrency()));
            $moneyToSell = new Money(BigDecimal::fromString($transferRequest->getMoneyToSellValue()), new Currency($transferRequest->getMoneyToSellCurrency()));

            $status = $this->accountService->transferFundsBetweenWallets($traderNumber, $moneyToBuy, $moneyToSell);

            $jsonString = $this->serializer->serialize($status, 'json');
            return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        } catch (RuntimeException $e) {
            $this->logger->error("Undefined Exception", ['exception' => $e]);
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * @Route("/accounts/withdraw/{traderNumber}", name="withdraw_funds", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Account"},
     *     summary="Withdraws funds from the account",
     *
     *     @OA\RequestBody(
     *         description="Funds to withdraw",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=FundsToWithdraw::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funds withdrawn",
     *     )
     * )
     */
    public function withdrawFunds(string $traderNumber, Request $request): Response
    {
        try {
            $fundsToWithdraw = $this->serializer->deserialize($request->getContent(), FundsToWithdraw::class, 'json');

            $withdrawFundsCommand = new WithdrawFundsCommand(
                $traderNumber,
                BigDecimal::fromString($fundsToWithdraw->getValue()),
                new Currency($fundsToWithdraw->getCurrency()));

            $status = $this->accountService->withdrawFunds($withdrawFundsCommand);

            $jsonString = $this->serializer->serialize($status, 'json');
            return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        } catch (RuntimeException $e) {
            $this->logger->error("Undefined Exception", ['exception' => $e]);
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * @Route("/accounts/transfer/{fromAccountId}/{toAccountId}", name="transfer_funds_between_account", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Account"},
     *     summary="Transfers funds between accounts",
     *
     *     @OA\RequestBody(
     *         description="Transfer fund request data",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=TransferFundRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funds transferred",
     *     )
     * )
     */
    public function transferFundsBetweenAccount(string $fromAccountId, string $toAccountId, Request $request): Response
    {
        try {
            $transferFundRequest = $this->serializer->deserialize($request->getContent(), TransferFundRequest::class, 'json');

            $transferFundsBetweenAccountCommand = new TransferFundsBetweenAccountCommand(
                $fromAccountId,
                $toAccountId,
                BigDecimal::fromString($transferFundRequest->getAmount()),
                $transferFundRequest->getCurrency());

            $status = $this->accountService->transferFundsBetweenAccount($transferFundsBetweenAccountCommand);

            $jsonString = $this->serializer->serialize($status, 'json');
            return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        } catch (RuntimeException $e) {
            $this->logger->error("Undefined Exception", ['exception' => $e]);
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * @Route("/accounts/activate/{accountId}", name="activate_account", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Account"},
     *     summary="Activates an account",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Account activated",
     *     )
     * )
     */
    public function activateAccount(string $accountId): Response
    {
        $activateAccountStatus = $this->accountService->activateAccount($accountId);
        $jsonString = $this->serializer->serialize($activateAccountStatus, 'json');
        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }

    /**
     * @Route("/accounts/{traderNumber}/wallets", name="get_wallet_data_by_trader_number", methods={"GET"})
     *
     * @OA\Get(
     *     tags={"Account"},
     *     summary="Gets wallet data by trader number",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Wallet data",
     *         @OA\JsonContent(type="array", @OA\Items(ref=@Model(type=WalletData::class)))
     *     )
     * )
     */
    public function getWalletDataByTraderNumber(string $traderNumber): Response
    {

        $allWalletsForTrader = $this->accountService->getAllWalletsForTrader($traderNumber);
        $jsonString = $this->serializer->serialize($allWalletsForTrader, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}
