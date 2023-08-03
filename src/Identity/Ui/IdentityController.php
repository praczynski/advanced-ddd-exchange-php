<?php

namespace App\Identity\Ui;

use App\Identity\Application\CreateIdentityCommand;
use App\Identity\Application\IdentityApplicationService;
use App\Identity\Domain\Identity;
use App\Identity\Domain\IdentityRepository;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use App\Kernel\IdentityId;
use App\Kernel\Money;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class IdentityController extends AbstractController
{
    private IdentityRepository $identityRepository;
    private IdentityApplicationService $identityApplicationService;

    public function __construct(IdentityRepository $identityRepository, IdentityApplicationService $identityApplicationService)
    {
        $this->identityRepository = $identityRepository;
           $this->identityApplicationService = $identityApplicationService;

    }

    /**
     * @Route("/identity/new{uuid}", name="identity_new", methods={"POST"})
     *
     * @Operation(
     *     tags={"Identity"},
     *     summary="Creates a new identity",
     *
     *     security={{"api_key_security_example": {}}}
     * )
     */
    public function index(string $uuid): Response
    {
        $identity = new Identity();

        $this->identityRepository->save($identity);

        $money1 = new Money(new BigDecimal('101.22'), new Currency('EUR'));
        $money2 = new Money(new BigDecimal('101.25'), new Currency('EUR'));

        return new Response($identity->identityId()->toString(), Response::HTTP_OK);
    }

    /**
     * @Route("/identity/create", name="identity_create", methods={"POST"})
     *
     * @Operation(
     *     tags={"Identity"},
     *     summary="Creates a new identity",
     *
     *     security={{"api_key_security_example": {}}}
     * )
     */
    public function createIdentity(): Response
    {
        $createIdentityCommand = new CreateIdentityCommand(
            IdentityId::generate(),
            '12345678901',
            'Jan',
            'Kowalski', 'kontakt@coztymit.pl'
        );

        $this->identityApplicationService->createIdentity($createIdentityCommand);
        return new Response('OK', Response::HTTP_OK);
    }

}