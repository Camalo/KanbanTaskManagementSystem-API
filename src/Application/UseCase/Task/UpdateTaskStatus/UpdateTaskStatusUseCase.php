<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTaskStatus;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\CannotUpdateStatusException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UpdateTaskStatusUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private TaskRepositoryInterface $taskRepository,
        #[Target('task.state_machine')]
        private WorkflowInterface $taskWorkflow,
    ) {}

    public function __invoke(UpdateTaskStatusRequest $request): void
    {
        $task = $this->taskRepository->findById($request->id);

        if (!$task) {
            throw new TaskNotFoundException($request->id);
        }

        if (!$this->auth->isGranted('TASK_MANAGE', $task)) {
            throw new AccessDeniedException();
        }

        if (!$this->taskWorkflow->can(
            subject: $task,
            transitionName: $request->transition
        )) {
            throw new CannotUpdateStatusException($task->getStatus(), $request->transition);
        }

        $this->taskWorkflow->apply(
            subject: $task,
            transitionName: $request->transition
        );

        $this->taskRepository->save($task);
    }
}
