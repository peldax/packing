<?php

namespace App\Controller;

use App\OpenApi\Dto\PackInput;
use App\OpenApi\PackEndpointService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/pack', name: self::ROUTE_NAME, methods: ['GET', 'POST'])]
final readonly class PackController
{
    public const string ROUTE_NAME = 'openapi_pack';

    public function __construct(
        private PackEndpointService $packEndpointService,
    )
    {
    }

    public function __invoke(
        Request $request,
        #[MapRequestPayload]
        PackInput $input,
    ) : Response
    {
        // TODO: atributy na kontrolleru a DTOčkách pro vygenerování swaggeru
        return new JsonResponse($this->packEndpointService->pack($input));
    }
}
