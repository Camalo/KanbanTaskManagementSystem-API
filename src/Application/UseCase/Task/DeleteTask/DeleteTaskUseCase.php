<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\DeleteTask;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DeleteTaskUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private TaskRepositoryInterface $repository
    ) {}

    public function __invoke(DeleteTaskRequest $request): void
    {
        $task = $this->repository->findById($request->id);

        if (!$task) {
            throw new TaskNotFoundException($request->id);
        }

        if (!$this->auth->isGranted('TASK_MANAGE', $task)) {
            throw new AccessDeniedException();
        }

        $this->repository->delete($task);
    }
}
