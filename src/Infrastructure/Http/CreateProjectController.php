<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\CreateProject\CreateProjectRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\CreateProject\CreateProjectUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\ProjectFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(ProjectFieldSets::REQUIRED, ProjectFieldSets::OPTIONAL)]
#[Route(
    '/api/projects',
    name: 'create_project',
    methods: ['POST']
)]
class CreateProjectController extends AbstractController
{
    public function __construct(private CreateProjectUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');

        ($this->useCase)(
            new CreateProjectRequest(
                title: $data['title'],
                description: $data['description']
            )
        );

        return new JsonResponse(
            data: ['success' => 'Проект создан'],
            status: Response::HTTP_CREATED
        );
    }
}
