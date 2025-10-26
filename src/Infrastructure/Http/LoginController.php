<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\Login\LoginRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\Login\LoginUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\LoginFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(LoginFieldSets::REQUIRED)]
#[Route(
    '/api/auth/login',
    name: 'login',
    methods: ['POST']
)]
class LoginController extends AbstractController
{
    public function __construct(private LoginUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');


        $response = ($this->useCase)(new LoginRequest(
            email: $data['email'],
            password: $data['password'],
            ip: $request->getClientIp()
        ));

        return new JsonResponse(
            [
                'access_token' => $response->accessToken,
                'refresh_token' => $response->refreshToken,
                'token_type' => 'Bearer'
            ],
            status: Response::HTTP_OK
        );
    }
}
