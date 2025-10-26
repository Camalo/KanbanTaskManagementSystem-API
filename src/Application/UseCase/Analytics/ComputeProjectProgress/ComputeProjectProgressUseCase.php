<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectProgress;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics\AnalyticsService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class ComputeProjectProgressUseCase
{
    public function __construct(
        private Security $security,
        private AnalyticsService $analytics,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(ComputeProjectProgressRequest $request): ComputeProjectProgressResponse
    {
        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
        }

        if (!$this->auth->isGranted('PROJECT_MANAGE', $project)) {
            throw new AccessDeniedException();
        }

        $response = $this->analytics->computeProjectProgress($project);

        return new ComputeProjectProgressResponse(
            progress: $response->progress,
            allTasksCount: $response->allTasksCount,
            completedTasksCount: $response->completedTasksCount
        );
    }
}
