<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\SearchTask\SearchTaskRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\SearchTask\SearchTaskUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/search',
    name: 'task_search',
    methods: ['GET']
)]
class SearchTaskController extends AbstractController
{
    public function __construct(
        private SearchTaskUseCase $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        // TODO??
        $query = $request->query->get('query') ?? '';

        if ($query === '') {
            throw new BadRequestHttpException('Не передана строка для поиска');
        }

        $response = ($this->useCase)(
            new SearchTaskRequest(
                query: $query
            )
        );

        return new JsonResponse(
            $response->items,
            Response::HTTP_OK
        );
    }
}
