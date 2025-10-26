<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\DeleteTask\DeleteTaskRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\DeleteTask\DeleteTaskUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/projects/{projectId}/tasks/{id}',
    name: 'delete_task',
    methods: ['DELETE'],
    requirements: [
        'projectId' => '\d+',
        'id' => '\d+'
    ]
)]
class DeleteTaskController extends AbstractController
{
    public function __construct(private DeleteTaskUseCase $useCase) {}

    public function __invoke(int $id): JsonResponse
    {
        ($this->useCase)(
            new DeleteTaskRequest(
                $id
            )
        );

        return new JsonResponse(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
