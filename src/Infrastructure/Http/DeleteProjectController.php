<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\DeleteProject\DeleteProjectRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\DeleteProject\DeleteProjectUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/api/projects/{projectId}',
    name: 'delete_project',
    methods: ['DELETE'],
    requirements: ['projectId' => '\d+']
)]
class DeleteProjectController extends AbstractController
{
    public function __construct(private DeleteProjectUseCase $useCase) {}

    public function __invoke(int $projectId): JsonResponse
    {
        ($this->useCase)(
            new DeleteProjectRequest(
                $projectId
            )
        );

        return new JsonResponse(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
