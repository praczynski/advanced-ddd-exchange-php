<?php

namespace App\Currency\Ui;

use App\Currency\Application\AddCurrencyPairStatus;
use App\Currency\Application\AddCurrencyPairWithRateResponse;
use App\Currency\Application\CurrencyPairApplicationService;
use App\Currency\Application\DeactivateCurrencyPairStatus;
use App\Currency\Application\UpdateCurrencyPairRateStatus;
use App\Currency\Domain\CurrencyPair;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyPairController extends AbstractController
{
    private CurrencyPairApplicationService $currencyPairApplicationService;
    private SerializerInterface $serializer;

    public function __construct(CurrencyPairApplicationService $currencyPairApplicationService, SerializerInterface $serializer)
    {
        $this->currencyPairApplicationService = $currencyPairApplicationService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/currency-pair/add", name="add_currency_pair", methods={"POST"})
     *
     * @Operation(
     *     tags={"Currency Pair"},
     *     summary="Adds a new currency pair",
     *     @OA\RequestBody(
     *         description="Currency Pair Request",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=CurrencyPairRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency pair addition status",
     *         @OA\JsonContent(ref=@Model(type=AddCurrencyPairStatus::class))
     *     )
     * )
     * @throws Exception
     */
    public function addCurrencyPair(Request $request): Response
    {

        $currencyPairRequest = $this->serializer->deserialize($request->getContent(), CurrencyPairRequest::class, 'json');

        $status = $this->currencyPairApplicationService->addCurrencyPair(
            Currency::fromString($currencyPairRequest->getBaseCurrency()),
            Currency::fromString($currencyPairRequest->getTargetCurrency())
        );

        $jsonString = $this->serializer->serialize($status, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/currency-pair/add-with-rate", name="add_currency_pair_with_rate", methods={"POST"})
     *
     * @Operation(
     *     tags={"Currency Pair"},
     *     summary="Adds a new currency pair with an adjusted rate",
     *     @OA\RequestBody(
     *         description="Currency Pair with Rate Request",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=CurrencyPairWithRateRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency pair with rate addition status",
     *         @OA\JsonContent(ref=@Model(type=AddCurrencyPairWithRateResponse::class))
     *     )
     * )
     * @throws Exception
     */
    public function addCurrencyPairWithRate(Request $request): Response
    {

        $currencyPairWithRateRequest = $this->serializer->deserialize($request->getContent(), CurrencyPairWithRateRequest::class, 'json');

        $response = $this->currencyPairApplicationService->addCurrencyPairWithRate(
            BigDecimal::fromString($currencyPairWithRateRequest->getAdjustedRate()),
            Currency::fromString($currencyPairWithRateRequest->getBaseCurrency()) ,
            Currency::fromString($currencyPairWithRateRequest->getTargetCurrency())
        );

        $jsonString = $this->serializer->serialize($response, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/currency-pair/update-rate", name="update_currency_pair_rate", methods={"PUT"})
     *
     * @Operation(
     *     tags={"Currency Pair"},
     *     summary="Updates the rate for an existing currency pair",
     *     @OA\RequestBody(
     *         description="Currency Pair with Rate Update Request",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=CurrencyPairWithRateRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency pair rate update status",
     *         @OA\JsonContent(ref=@Model(type=UpdateCurrencyPairRateStatus::class))
     *     )
     * )
     * @throws Exception
     */
    public function updateCurrencyPairRate(Request $request): Response
    {

        $currencyPairWithRateRequest = $this->serializer->deserialize($request->getContent(), CurrencyPairWithRateRequest::class, 'json');

        $status = $this->currencyPairApplicationService->updateCurrencyPairRate(
            Currency::fromString($currencyPairWithRateRequest->getBaseCurrency()),
            Currency::fromString($currencyPairWithRateRequest->getTargetCurrency()),
            BigDecimal::fromString($currencyPairWithRateRequest->getAdjustedRate())
        );

        $jsonString = $this->serializer->serialize($status, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/currency-pair/deactivate/{currencyPairId}", name="deactivate_currency_pair", methods={"POST"})
     *
     * @Operation(
     *     tags={"Currency Pair"},
     *     summary="Deactivates an existing currency pair by ID",
     *     @OA\Parameter(
     *         name="currencyPairId",
     *         in="path",
     *         description="ID of the currency pair to be deactivated",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Currency pair deactivation status",
     *         @OA\JsonContent(ref=@Model(type=DeactivateCurrencyPairStatus::class))
     *     )
     * )
     * @throws Exception
     */
    public function deactivateCurrencyPair(string $currencyPairId): Response
    {

        $status = $this->currencyPairApplicationService->deactivateCurrencyPair($currencyPairId);

        $jsonString = $this->serializer->serialize($status, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/currency-pair/all", name="get_all_currency_pairs", methods={"GET"})
     *
     * @Operation(
     *     tags={"Currency Pair"},
     *     summary="Retrieve all currency pairs",
     *     @OA\Response(
     *         response=200,
     *         description="List of all currency pairs",
     *         @OA\JsonContent(type="array", @OA\Items(ref=@Model(type=CurrencyPair::class)))
     *     )
     * )
     */
    public function getAllCurrencyPairs(): Response
    {
        $currencyPairs = $this->currencyPairApplicationService->getAllCurrencyPairs();

        $jsonString = $this->serializer->serialize($currencyPairs, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/currency-pair/{baseCurrency}/{targetCurrency}", name="get_currency_pair", methods={"GET"})
     *
     * @Operation(
     *     tags={"Currency Pair"},
     *     summary="Retrieve a specific currency pair based on base and target currencies",
     *     @OA\Parameter(
     *         name="baseCurrency",
     *         in="path",
     *         description="Base currency",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="targetCurrency",
     *         in="path",
     *         description="Target currency",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details of the specific currency pair",
     *         @OA\JsonContent(ref=@Model(type=CurrencyPair::class))
     *     )
     * )
     */
    public function getCurrencyPair(string $baseCurrency, string $targetCurrency): Response
    {

        $currencyPair = $this->currencyPairApplicationService->getCurrencyPair(Currency::fromString($baseCurrency), Currency::fromString(strtoupper($targetCurrency)));

        $jsonString = $this->serializer->serialize($currencyPair, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


}
