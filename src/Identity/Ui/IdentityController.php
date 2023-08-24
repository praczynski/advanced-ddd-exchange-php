<?php

namespace App\Identity\Ui;

use App\Identity\Application\CreateIdentityCommand;
use App\Identity\Application\IdentityApplicationService;
use App\Kernel\IdentityId;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class IdentityController extends AbstractController
{
    private IdentityApplicationService $identityApplicationService;
    private SerializerInterface $serializer;

    public function __construct(IdentityApplicationService $identityApplicationService, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->identityApplicationService = $identityApplicationService;
    }

    /**
     * @Route("/identities/all", methods={"GET"})
     *
     * @Operation(
     *      tags={"Identity"},
     *      summary="Get all identity IDs",
     *
     *      @OA\Response(
     *          response=200,
     *          description="List of Identity IDs",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
     *          )
     *      )
     * )
     */
    public function getAllIdentityIds(): JsonResponse {
        $identityIds = $this->identityApplicationService->getAllIdentityIds();
        return new JsonResponse($identityIds);
    }

    /**
     * @Route("/identity", name="identity_create", methods={"POST"})
     *
     * @Operation(
     *     tags={"Identity"},
     *     summary="Creates a new identity",
     *
     *    @OA\RequestBody(
     *          description="Identity data",
     *          required=true,
     *          @OA\JsonContent(ref=@Model(type=IdentityRequest::class))
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Identity created",
     *      )
     * )
     */
    public function createIdentity(Request $request): Response
    {
        $identityRequest = $this->serializer->deserialize($request->getContent(), IdentityRequest::class, 'json');

        $command = new CreateIdentityCommand($identityRequest->getPesel(), $identityRequest->getFirstName(), $identityRequest->getSurname(), $identityRequest->getEmail());
        $status = $this->identityApplicationService->createIdentity($command);

        $jsonString = $this->serializer->serialize($status, 'json');
        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/identity/{identityId}", methods={"GET"})
     *
     * @Operation(
     *      tags={"Identity"},
     *      summary="Get the identity",
     *
     *      @OA\Parameter(
     *          name="identityId",
     *          in="path",
     *          description="Identity ID to retrieve",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
     *
     *      ),
     *
     *      security={{"api_key_security_example": {}}}
     * )
     */
    public function getIdentity(string $identityId):  Response
    {
        $identityResponse = $this->identityApplicationService->getIdentity(new IdentityId(Uuid::fromString($identityId)));
        $jsonString = $this->serializer->serialize($identityResponse, 'json');

        //TODO how to use $jsonResponse = new JsonResponse($identityResponse);

       return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }



}