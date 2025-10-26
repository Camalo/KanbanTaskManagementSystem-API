<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskHistory;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\TaskNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\UnknownUserException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskHistoryRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GetTaskHistoryUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private TaskRepositoryInterface $taskRepository,
        private TaskHistoryRepositoryInterface $historyRepository
    ) {}

    public function __invoke(GetTaskHistoryRequest $request): GetTaskHistoryResponse
    {
        // TODO вынести из каждого useCase этот блок кода
        $user = $this->security->getUser();

        if (!$user instanceof \Kamalo\KanbanTaskManagementSystem\Domain\Entity\User) {
            throw new UnknownUserException();
        }
        $timezone = new \DateTimeZone($user->getTimezone());

        $task = $this->taskRepository->findById($request->id);

        if ($task === null) {
            throw new TaskNotFoundException($request->id);
        }

        if (!$this->auth->isGranted('TASK_VIEW', $task)) {
            throw new AccessDeniedException();
        }

        $history = $this->historyRepository->findByTask($task);

        $entries = [];

        foreach ($history as $entry) {

            $entries[] = new TaskHistoryEntry(
                $entry->getAction(),
                $entry->getChanges(),
                $entry->getPerformedBy()?->getId(),
                $entry->getPerformedBy()?->getName()->getFullName(),
                $entry->getCreatedAt()->setTimezone($timezone)->format('d.m.Y H:i:s')
            );
        }

        return new GetTaskHistoryResponse($entries);
    }
}
