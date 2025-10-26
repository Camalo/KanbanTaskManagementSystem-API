<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\GetProjectList\GetProjectListUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/projects',
    name: 'project_list',
    methods: ['GET']
)]
class GetProjectListController extends AbstractController
{
    public function __construct(private GetProjectListUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $response =  ($this->useCase)();

        return new JsonResponse(
            $response->items,
            Response::HTTP_OK
        );
    }
}
