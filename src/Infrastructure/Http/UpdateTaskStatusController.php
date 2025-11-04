<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\CannotUpdateStatusException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTaskStatus\UpdateTaskStatusRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTaskStatus\UpdateTaskStatusUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\UpdateStatusFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(UpdateStatusFieldSets::REQUIRED)]
#[Route(
    '/api/projects/{projectId}/tasks/{id}/status',
    name: 'update_status',
    methods: ['PATCH'],
    requirements: [
        'projectId' => '\d+',
        'id' => '\d+'
    ]
)]
class UpdateTaskStatusController extends AbstractController
{
    public function __construct(private UpdateTaskStatusUseCase $useCase) {}

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');

        $useCaseRequest = new UpdateTaskStatusRequest(
            id: $id,
            transition: $data['transition']
        );

        ($this->useCase)($useCaseRequest);

        return new JsonResponse(
            data: ['message' => 'Статус задачи обновлен'],
            status: Response::HTTP_OK
        );
    }
}
