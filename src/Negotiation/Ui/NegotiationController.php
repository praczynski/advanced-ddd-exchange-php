<?php

namespace App\Negotiation\Ui;


use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;
use App\Negotiation\Application\CreateNegotiationCommand;
use App\Negotiation\Application\CreateNegotiationStatus;
use App\Negotiation\Application\FindAcceptedActiveNegotiationRateCommand;
use App\Negotiation\Application\NegotiationApplicationService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;

class NegotiationController extends AbstractController
{
    private NegotiationApplicationService $negotiationApplicationService;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function __construct(
        NegotiationApplicationService $negotiationApplicationService,
        SerializerInterface           $serializer,
        LoggerInterface               $logger
    )
    {
        $this->negotiationApplicationService = $negotiationApplicationService;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }


    /**
     * @Route("/negotiations/create", name="create_negotiation", methods={"POST"})
     *
     * @Operation(
     *     tags={"Negotiation"},
     *     summary="Creates a new negotiation",
     *     @OA\RequestBody(
     *         description="Negotiation Request",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=NegotiationRequest::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Negotiation created",
     *         @OA\JsonContent(ref=@Model(type=CreateNegotiationStatus::class))
     *     )
     * )
     * @throws Exception
     */
    public function createNegotiation(Request $request): Response
    {
        $negotiationRequest = $this->serializer->deserialize($request->getContent(), NegotiationRequest::class, 'json');

        $createNegotiationCommand = new CreateNegotiationCommand(
            IdentityId::fromString($negotiationRequest->getIdentityId()),
            Currency::fromString($negotiationRequest->getBaseCurrency()),
            Currency::fromString($negotiationRequest->getTargetCurrency()),
            BigDecimal::fromString($negotiationRequest->getProposedExchangeAmount()),
            Currency::fromString($negotiationRequest->getProposedExchangeCurrency()),
            BigDecimal::fromString($negotiationRequest->getProposedRate())
        );

        $status = $this->negotiationApplicationService->createNegotiation($createNegotiationCommand);
        $jsonString = $this->serializer->serialize($status, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/negotiations/{negotiationId}/approve", name="approve_negotiation", methods={"PUT"})
     *
     * @Operation(
     *     tags={"Negotiation"},
     *     summary="Approves a negotiation",
     *     @OA\Parameter(
     *         name="negotiationId",
     *         in="path",
     *         required=true,
     *         description="ID of the negotiation to approve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="operatorId",
     *         in="query",
     *         required=true,
     *         description="ID of the operator approving the negotiation",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Negotiation approved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="OK")
     *         )
     *     )
     * )
     *
     * @param string $negotiationId
     * @param Request $request
     *
     * @return Response
     */
    public function approveNegotiation(string $negotiationId, Request $request): Response
    {
        $operatorId = $request->query->get('operatorId');

        $this->negotiationApplicationService->approveNegotiation($negotiationId, $operatorId);

        return new Response("OK", Response::HTTP_OK);
    }

    /**
     * @Route("/negotiations/{negotiationId}/reject", name="reject_negotiation", methods={"PUT"})
     *
     * @Operation(
     *     tags={"Negotiation"},
     *     summary="Rejects a negotiation",
     *     @OA\Parameter(
     *         name="negotiationId",
     *         in="path",
     *         required=true,
     *         description="ID of the negotiation to reject",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="operatorId",
     *         in="query",
     *         required=true,
     *         description="ID of the operator rejecting the negotiation",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Negotiation rejected successfully"
     *     )
     * )
     *
     * @param string $negotiationId
     * @param Request $request
     *
     * @return Response
     */
    public function rejectNegotiation(string $negotiationId, Request $request): Response
    {
        $operatorId = $request->query->get('operatorId');

        $this->negotiationApplicationService->rejectNegotiation($negotiationId, $operatorId);

        return new Response(null, Response::HTTP_OK);
    }

    /**
     * @Route("/negotiations/{id}", name="get_negotiation", methods={"GET"})
     *
     * @Operation(
     *     tags={"Negotiation"},
     *     summary="Retrieves details of a specific negotiation",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the negotiation to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details of the negotiation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="rate", type="number", format="decimal", example="1.2345", description="The rate of the negotiation"),
     *             @OA\Property(property="status", type="string", example="SUCCESS", description="Status of the retrieval, can be 'SUCCESS' or 'CANNOT_FIND_RATE'"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Negotiation not found"
     *     )
     * )
     *
     * @param string $id
     *
     * @return Response
     */
    public function getNegotiation(string $id): Response
    {
        $negotiationRateResponse = $this->negotiationApplicationService->getNegotiationRateIfApproved($id);

        $jsonString = $this->serializer->serialize($negotiationRateResponse, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/negotiations/find-approved", name="find_accepted_negotiation", methods={"POST"})
     *
     * @Operation(
     *     tags={"Negotiation"},
     *     summary="Finds accepted negotiations",
     *     @OA\RequestBody(
     *         description="Details needed to find an accepted negotiation",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="identityId", type="string", description="ID of the identity"),
     *             @OA\Property(property="baseCurrency", type="string", description="Base currency for the negotiation"),
     *             @OA\Property(property="targetCurrency", type="string", description="Target currency for the negotiation"),
     *             @OA\Property(property="proposedExchangeAmount", type="string", description="Proposed amount for exchange"),
     *             @OA\Property(property="proposedExchangeCurrency", type="string", description="Currency proposed for exchange"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Result of the search",
     *         @OA\JsonContent(
     *             @OA\Property(property="rate", type="string", description="The rate of the negotiation if found"),
     *             @OA\Property(property="status", type="string", example="SUCCESS", description="Status of the search, can be 'SUCCESS' or 'CANNOT_FIND_RATE'")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Negotiation not found"
     *     )
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function findAcceptedNegotiation(Request $request): Response
    {

        $findAcceptedNegotiationRequest = $this->serializer->deserialize($request->getContent(), FindAcceptedNegotiationRequest::class, 'json');

        $findAcceptedActiveNegotiationRateCommand = new FindAcceptedActiveNegotiationRateCommand(
            IdentityId::fromString($findAcceptedNegotiationRequest->getIdentityId()),
            Currency::fromString($findAcceptedNegotiationRequest->getBaseCurrency()),
            Currency::fromString($findAcceptedNegotiationRequest->getTargetCurrency()),
            BigDecimal::fromString($findAcceptedNegotiationRequest->getProposedExchangeAmount()),
            Currency::fromString($findAcceptedNegotiationRequest->getProposedExchangeCurrency())
        );

        $negotiationRateResponse = $this->negotiationApplicationService->findAcceptedActiveNegotiationRate($findAcceptedActiveNegotiationRateCommand);

        $jsonString = $this->serializer->serialize($negotiationRateResponse, 'json');

        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}
