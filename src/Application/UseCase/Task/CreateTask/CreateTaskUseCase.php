<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\CreateTask;

use DateTimeImmutable;
use Exception;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AssigneeNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AssigneeNotInProjectException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\UserRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CreateTaskUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private UserRepositoryInterface $userRepository,
        private TaskRepositoryInterface $taskRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(CreateTaskRequest $request): CreateTaskResponse
    {
        $owner = $this->security->getUser();

        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
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
        // Дата только в будущем
        $dueDate = $request->dueDate;

        if ($dueDate !== null) {
            $dueDate = new \DateTimeImmutable($request->dueDate);

            if (!$dueDate) {
                throw new Exception("Неверный формат даты: {$request->dueDate}");
            }

            if ($dueDate <= new DateTimeImmutable('today')) {
                throw new Exception("Дата должна быть в будущем, получено {$request->dueDate}.");
            }
        }

        $description = null;
        if ($request->description !== null) {
            $description = new Description($request->description);
        }
        // Создание задачи
        $task = new Task(
            id: null,
            title: new Title($request->title),
            project: $project,
            description: $description,
            owner: $owner,
            assignee: $assignee,
            dueDate: $dueDate
        );

        $task->updatePriority(new TaskPriority($request->priority));

        if (!$this->auth->isGranted('TASK_CREATE', $task)) {
            throw new AccessDeniedException();
        }

        $this->taskRepository->save($task);

        return new CreateTaskResponse(
            $task->getId(),
            'Задача с id ' . $task->getId() . ' успешно создана'
        );
    }
}
