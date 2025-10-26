<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\DeleteProject;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DeleteProjectUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $repository
    ) {}

    public function __invoke(DeleteProjectRequest $request): void
    {        
        $project = $this->repository->findById($request->id);

        if (!$project) {
            throw new ProjectNotFoundException($request->id);
        }

         if (!$this->auth->isGranted('PROJECT_MANAGE', $project)) {
            throw new AccessDeniedException();
        }

        $this->repository->delete($project);
    }
}
