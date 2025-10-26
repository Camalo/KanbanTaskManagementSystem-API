<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskDetail\GetTaskDetailRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskDetail\GetTaskDetailUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/projects/{projectId}/tasks/{id}',
    name: 'task_detail',
    methods: ['GET'],
    requirements: [
        'projectId' => '\d+',
        'id' => '\d+'
    ]
)]
class GetTaskDetailController extends AbstractController
{
    public function __construct(private GetTaskDetailUseCase $useCase) {}

    public function __invoke(int $projectId, int $id): JsonResponse
    {
        $response =  ($this->useCase)(
            new GetTaskDetailRequest(
                $id,
                $projectId
            )
        );

        return new JsonResponse(
            $response,
            Response::HTTP_OK
        );
    }
}
