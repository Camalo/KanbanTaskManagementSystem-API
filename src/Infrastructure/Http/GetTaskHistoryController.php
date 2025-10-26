<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\UnknownUserException;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskHistory\GetTaskHistoryRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskHistory\GetTaskHistoryUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/projects/{projectId}/tasks/{id}/history',
    name: 'task_history',
    methods: ['GET'],
    requirements: [
        'projectId' => '\d+',
        'id' => '\d+'
    ]
)]
class GetTaskHistoryController extends AbstractController
{
    public function __construct(private GetTaskHistoryUseCase $useCase) {}

    public function __invoke(int $id): JsonResponse
    {
        $response =  ($this->useCase)(
            new GetTaskHistoryRequest(
                $id
            )
        );

        return new JsonResponse(
            $response,
            Response::HTTP_OK
        );
    }
}
