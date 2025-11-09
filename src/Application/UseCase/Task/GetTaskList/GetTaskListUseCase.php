<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskList;

use DateTimeZone;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\UnknownUserException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GetTaskListUseCase
{
    public function __construct(
        private Security $security,
        // private AuthorizationCheckerInterface $auth,
        private TaskRepositoryInterface $taskRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(GetTaskListRequest $request): GetTaskListResponse
    {
        // TODO 
        // $user = $this->security->getUser();

        // if (!$user instanceof \Kamalo\KanbanTaskManagementSystem\Domain\Entity\User) {
        //     throw new UnknownUserException();
        // }
        // $timezone = new \DateTimeZone($user->getTimezone());
        $timezone = new DateTimeZone("Europe/Moscow");

        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
        }

        $filters = ['project' => $project];

        if (!$request->showCanceled) {
            $filters['status'] = [
                'operator' => '!=',
                'value' => 'canceled'
            ];
        }
        $tasks = $this->taskRepository->find($filters);

        // TODO А если нет задач
        // if (!$this->auth->isGranted('TASK_VIEW', $tasks[0])) {
        //     throw new AccessDeniedException();
        // }

        $responseArray = [];

        foreach ($tasks as $key => $task) {
            $status = $task->getStatus();
            $priority = new TaskPriority($task->getPriority());

            $responseArray[$status][$key]['id'] = $task->getId();
            $responseArray[$status][$key]['title'] = $task->getTitle()->getValue();
            $responseArray[$status][$key]['description'] = $task->getDescription()?->getValue();
            $responseArray[$status][$key]['owner'] = $task->getOwner()->getId();
            $responseArray[$status][$key]['assignee'] = $task->getAssignee()?->getId();
            $responseArray[$status][$key]['priority'] = $priority->getLabel();
            $responseArray[$status][$key]['priorityValue'] = $priority->getValue();
            $responseArray[$status][$key]['status'] = $task->getStatus();
            $responseArray[$status][$key]['dueDate'] = $task->getDueDate()?->setTimezone($timezone)->format('d.m.Y');
            $responseArray[$status][$key]['createdAt'] = $task->getCreatedAt()->setTimezone($timezone)->format('d.m.Y H:i:s');
            $responseArray[$status][$key]['updatedAt'] = $task->getUpdatedAt()?->setTimezone($timezone)->format('d.m.Y H:i:s');
            $responseArray[$status][$key]['completedAt'] = $task->getCompletedAt()?->setTimezone($timezone)->format('d.m.Y H:i:s');
        }


        return new GetTaskListResponse($responseArray);
    }
}
