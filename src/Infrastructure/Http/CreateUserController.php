<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\CreateUser\CreateUserRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\CreateUser\CreateUserUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\CreateUserFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(CreateUserFieldSets::REQUIRED, CreateUserFieldSets::OPTIONAL)]
#[Route(
    '/api/auth/register',
    name: 'register_user',
    methods: ['POST']
)]
class CreateUserController extends AbstractController
{
    public function __construct(private CreateUserUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');

        $response = ($this->useCase)(
            new CreateUserRequest(
                firstName: $data['firstName'],
                middleName: $data['middleName'] ?? null,
                lastName: $data['lastName'],
                email: $data['email'],
                password: $data['password'],
                timezone: $data['timezone']
            )
        );

        return new JsonResponse(
            data: ['message' => 'Пользователь с id ' . $response->id . ' успешно создан'],
            status: Response::HTTP_OK
        );
    }
}
