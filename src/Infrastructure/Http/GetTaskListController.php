<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskList\GetTaskListRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskList\GetTaskListUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/projects/{projectId}/tasks',
    name: 'task_list',
    methods: ['GET'],
    requirements: ['projectId' => '\d+']
)]
class GetTaskListController extends AbstractController
{
    public function __construct(private GetTaskListUseCase $useCase) {}

    public function __invoke(int $projectId, Request $request): JsonResponse
    {
        // TODO:: Принимаем параметры фильтра -> см. SearchTaskController
        // TODO:: Здесь немного другие фильтры, но не пагинация
        // В любом случае слишком задач не будет вываливаться, будет сортировка и ограничение кол-ва
            $response = ($this->useCase)(
                new GetTaskListRequest(
                    projectId: $projectId,
                    priority: null,
                    status: null
                )
            );
        
        return new JsonResponse(
            $response->items,
            Response::HTTP_OK
        );
    }
}
