<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AssigneeNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AssigneeNotInProjectException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectMemberAlreadyExistsException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTask\UpdateTaskRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTask\UpdateTaskUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\TaskFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(TaskFieldSets::REQUIRED, optionalFields: TaskFieldSets::OPTIONAL)]
#[Route(
    '/api/projects/{projectId}/tasks/{id}',
    name: 'update_task',
    methods: ['PUT'],
    requirements: [
        'projectId' => '\d+',
        'id' => '\d+'
    ]
)]
class UpdateTaskController extends AbstractController
{
    public function __construct(private UpdateTaskUseCase $useCase) {}

    public function __invoke(int $projectId, int $id, Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');

        ($this->useCase)(
            new UpdateTaskRequest(
                id: $id,
                projectId: $projectId,
                title: $data['title'],
                description: $data['description'],
                assignee: $data['assignee'],
                dueDate: $data['dueDate'],
                priority: $data['priority']
            )
        );

        // TODO:: Правильные ли коды статусов возвращаются
        return new JsonResponse(
            data: ['success' => 'Задача обновлена'],
            status: Response::HTTP_CREATED
        );
    }
}
