<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\CreateTask\CreateTaskRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\CreateTask\CreateTaskUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\TaskFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(TaskFieldSets::REQUIRED, TaskFieldSets::OPTIONAL)]
#[Route(
    '/api/projects/{projectId}/tasks',
    name: 'create_task',
    methods: ['POST'],
    requirements: ['projectId' => '\d+']
)]
class CreateTaskController extends AbstractController
{
    public function __construct(private CreateTaskUseCase $useCase) {}

    public function __invoke(int $projectId, Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');

        ($this->useCase)(
            new CreateTaskRequest(
                title: $data['title'],
                projectId: $projectId,
                description: $data['description'],
                assignee: $data['assignee'],
                dueDate: $data['dueDate'],
                priority: $data['priority']
            )
        );

        return new JsonResponse(
            data: ['success' => 'Задача создана'],
            status: Response::HTTP_CREATED
        );
    }
}
