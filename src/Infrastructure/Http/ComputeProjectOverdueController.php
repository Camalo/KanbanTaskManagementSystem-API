<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectOverdue\ComputeProjectOverdueRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectOverdue\ComputeProjectOverdueUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(
    '/api/analytics/projects/{projectId}/overdue',
    name: 'project_overdue',
    methods: ['GET'],
    requirements: ['projectId' => '\d+']
)]
class ComputeProjectOverdueController extends AbstractController
{
    public function __construct(private ComputeProjectOverdueUseCase $useCase) {}

    public function __invoke(int $projectId): JsonResponse
    {
        $response = ($this->useCase)(
            new ComputeProjectOverdueRequest(
                projectId: $projectId
            )
        );

        return new JsonResponse(
            $response,
            Response::HTTP_OK
        );
    }
}
