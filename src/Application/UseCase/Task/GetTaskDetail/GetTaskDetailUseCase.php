<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskDetail;

use Exception;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\UnknownUserException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GetTaskDetailUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private TaskRepositoryInterface $taskRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(GetTaskDetailRequest $request): GetTaskDetailResponse
    {
        // TODO вынести из каждого useCase этот блок кода
        $user = $this->security->getUser();

        if (!$user instanceof \Kamalo\KanbanTaskManagementSystem\Domain\Entity\User) {
            throw new UnknownUserException();
        }
        $timezone = new \DateTimeZone($user->getTimezone());

        // TODO права доступа к проекту
        $project = $this->projectRepository->findById($request->projectId);

        if(!$project){
            throw new ProjectNotFoundException($request->projectId);
        }

        $task = $this->taskRepository->findById($request->id);

        if (!$task) {
            throw new TaskNotFoundException($request->id);
        }

        if (!$this->auth->isGranted('TASK_VIEW', $task)) {
            throw new AccessDeniedException();
        }

        return new GetTaskDetailResponse(
            id: $task->getId(),
            projectId: $task->getProject()->getId(),
            title: $task->getTitle()->getValue(),
            description: $task->getDescription()?->getValue(),
            owner: $task->getOwner()->getId(),
            assignee: $task->getAssignee()?->getId(),
            priority: new TaskPriority($task->getPriority())->getLabel(),
            status: $task->getStatus(),
            dueDate: $task->getDueDate()?->setTimezone($timezone)->format('d-m-Y'),
            createdAt: $task->getCreatedAt()->setTimezone($timezone)->format('d.m.Y H:i:s'),
            updatedAt: $task->getUpdatedAt()?->setTimezone($timezone)->format('d.m.Y H:i:s'),
            completedAt: $task->getCompletedAt()?->setTimezone($timezone)->format('d.m.Y H:i:s')
        );
    }
}
