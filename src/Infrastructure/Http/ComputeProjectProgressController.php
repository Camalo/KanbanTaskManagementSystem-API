<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectProgress\ComputeProjectProgressRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectProgress\ComputeProjectProgressUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/api/analytics/projects/{projectId}/progress',
    name: 'project_progress',
    methods: ['GET'],
    requirements: ['projectId' => '\d+']
)]
class ComputeProjectProgressController extends AbstractController
{
    public function __construct(private ComputeProjectProgressUseCase $useCase) {}

    public function __invoke(int $projectId): JsonResponse
    {
        $response = ($this->useCase)(
            new ComputeProjectProgressRequest(
                projectId: $projectId
            )
        );

        return new JsonResponse(
            $response,
            Response::HTTP_OK
        );
    }
}
