<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectPerformance\ComputeProjectPerformanceRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectPerformance\ComputeProjectPerformanceUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/api/analytics/projects/{projectId}/performance',
    name: 'project_perfomance',
    methods: ['GET'],
    requirements: ['projectId' => '\d+']
)]
class ComputeProjectPerformanceController extends AbstractController
{
    public function __construct(private ComputeProjectPerformanceUseCase $useCase) {}

    public function __invoke(int $projectId): JsonResponse
    {
        $response = ($this->useCase)(
            new ComputeProjectPerformanceRequest(
                projectId: $projectId
            )
        );

        return new JsonResponse(
            $response,
            Response::HTTP_OK
        );
    }
}
