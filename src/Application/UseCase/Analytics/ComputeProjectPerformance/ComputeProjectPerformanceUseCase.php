<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectPerformance;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics\AnalyticsService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class ComputeProjectPerformanceUseCase
{
    public function __construct(
        private Security $security,
        private AnalyticsService $analytics,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(ComputeProjectPerformanceRequest $request): ComputeProjectPerformanceResponse
    {
        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
        }

        if (!$this->auth->isGranted('PROJECT_MANAGE', $project)) {
            throw new AccessDeniedException();
        }

        $response = $this->analytics->ComputeProjectPerformance($project);

        return new ComputeProjectPerformanceResponse(
            totalSeconds: $response->total,
            days: $response->days,
            hours: $response->hours,
            minutes: $response->minutes,
            seconds: $response->seconds
        );
    }
}
