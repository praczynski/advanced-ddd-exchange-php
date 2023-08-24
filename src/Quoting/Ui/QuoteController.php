<?php

namespace App\Quoting\Ui;

use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;
use App\Quoting\Application\PrepareQuoteCommand;
use App\Quoting\Application\PrepareQuoteStatus;
use App\Quoting\Application\AcceptQuoteStatus;
use App\Quoting\Application\QuoteApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

class QuoteController extends AbstractController
{
    private QuoteApplicationService $quoteApplicationService;
    private SerializerInterface $serializer;

    public function __construct(QuoteApplicationService $quoteApplicationService, SerializerInterface $serializer)
    {
        $this->quoteApplicationService = $quoteApplicationService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/quotes/prepare-quote", name="prepare_quote", methods={"POST"})
     *
     * @Operation(
     *     tags={"Quote"},
     *     summary="Prepare a new quote",
     *     @OA\RequestBody(
     *         description="Prepare Quote Request",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=PrepareQuoteRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote preparation status",
     *         @OA\JsonContent(ref=@Model(type=PrepareQuoteStatus::class))
     *     )
     * )
     */
    public function prepareQuote(Request $request): Response
    {
        $quoteRequest = $this->serializer->deserialize($request->getContent(), PrepareQuoteRequest::class, 'json');
        $prepareQuoteCommand = new PrepareQuoteCommand(
            IdentityId::fromString($quoteRequest->getIdentityId()),
            BigDecimal::fromString($quoteRequest->getMoneyToExchangeValue()),
            Currency::fromString($quoteRequest->getMoneyToExchangeCurrency()),
            Currency::fromString($quoteRequest->getCurrencyToSell()),
            Currency::fromString($quoteRequest->getCurrencyToBuy()));

        $status = $this->quoteApplicationService->prepareQuote($prepareQuoteCommand);

        return $this->json($status);
    }

    /**
     * @Route("/quotes/{quoteId}/accept", name="accept_quote", methods={"PUT"})
     *
     * @Operation(
     *     tags={"Quote"},
     *     summary="Accept a quote",
     *     @OA\Parameter(
     *         name="quoteId",
     *         in="path",
     *         description="ID of the quote to be accepted",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote acceptance status",
     *         @OA\JsonContent(ref=@Model(type=AcceptQuoteStatus::class))
     *     )
     * )
     */
    public function acceptQuote(string $quoteId): Response
    {
        $status = $this->quoteApplicationService->acceptQuote($quoteId);

        return $this->json($status);
    }

    /**
     * @Route("/quotes/{quoteId}/reject", name="reject_quote", methods={"PUT"})
     *
     * @Operation(
     *     tags={"Quote"},
     *     summary="Reject a quote",
     *     @OA\Parameter(
     *         name="quoteId",
     *         in="path",
     *         description="ID of the quote to be rejected",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote rejection status",
     *         @OA\JsonContent(ref=@Model(type=AcceptQuoteStatus::class))
     *     )
     * )
     */
    public function rejectQuote(string $quoteId): Response
    {
        $status = $this->quoteApplicationService->reject($quoteId);

        return $this->json($status);
    }
}
