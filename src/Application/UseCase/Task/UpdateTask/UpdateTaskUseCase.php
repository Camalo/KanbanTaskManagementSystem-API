<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTask;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AssigneeNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AssigneeNotInProjectException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\UserRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UpdateTaskUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private UserRepositoryInterface $userRepository,
        private TaskRepositoryInterface $taskRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(UpdateTaskRequest $request): void
    {
        $task = $this->taskRepository->findById($request->id);

        if ($task === null) {
            throw new TaskNotFoundException($request->id);
        }

        $description = null;
        if ($request->description !== null) {
            $description = new Description($request->description);
        }

        $dueDate = null;
        if ($request->dueDate !== null) {
            $dueDate = new \DateTimeImmutable($request->dueDate);
        }

        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
        }

        if (!$this->auth->isGranted('TASK_MANAGE', $task)) {
            throw new AccessDeniedException();
        }
       
        $assignee = null;
        // Исполнитель
        if ($request->assignee !== null) {
            $assignee = $this->userRepository->findById($request->assignee);

            if (!$assignee) {
                throw new AssigneeNotFoundException($request->assignee);
            }
            if (!$project->hasMember($assignee)) {
                throw new AssigneeNotInProjectException();
            }
        }

        $task
            ->updateTitle(new Title($request->title))
            ->updateDescription($description)
            ->updateDueDate($dueDate)
            ->updatePriority(new TaskPriority($request->priority))
            ->updateAssignee($assignee);

        $this->taskRepository->save($task);
    }
}
