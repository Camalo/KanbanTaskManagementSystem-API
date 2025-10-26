<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectOverdue;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics\AnalyticsService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ComputeProjectOverdueUseCase
{
    public function __construct(
        private Security $security,
        private AnalyticsService $analytics,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(ComputeProjectOverdueRequest $request): ComputeProjectOverdueResponse
    {
        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
        }

        if (!$this->auth->isGranted('PROJECT_MANAGE', $project)) {
            throw new AccessDeniedException();
        }

        $response = $this->analytics->computeProjectOverdue($project);

        return new ComputeProjectOverdueResponse(
            total: $response->total,
            ids: $response->ids
        );
    }
}
