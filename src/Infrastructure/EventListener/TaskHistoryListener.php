<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\TaskHistory;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskHistoryRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::postPersist, priority: 0)]
#[AsDoctrineListener(event: Events::postUpdate, priority: 0)]
class TaskHistoryListener
{
    private const CREATE_FIELDS_ACTIONS = [
        'assignee' => 'assign',
        'dueDate'  => 'change_due_date',
        'priority' => 'change_priority',
    ];

    private const ACTION_MAP = [
        'assignee'    => 'assign',
        'dueDate'     => 'change_due_date',
        'priority'    => 'change_priority',
        'status'      => 'change_status',
        'title'       => 'update',
        'description' => 'update',
    ];

    public function __construct(
        private Security $security,
        private TaskHistoryRepositoryInterface $historyRepository
    ) {}
    
    public function postPersist(PostPersistEventArgs $args): void
    {
        $task = $args->getObject();

        if (!$task instanceof Task) {
            return;
        }

        $user = $this->security->getUser();

        $this->historyRepository->save(
            new TaskHistory(
                task: $task,
                action: 'create',
                performedBy: $user,
                changes: []
            )
        );

        foreach (self::CREATE_FIELDS_ACTIONS as $field => $action) {

            $value = $this->normalizeField($field, $task->{'get' . ucfirst($field)}());

            if ($value === null) continue;

            $this->historyRepository->save(
                new TaskHistory(
                    task: $task,
                    action: $action,
                    performedBy: $user,
                    changes: [
                        'old' => null,
                        'new' => $value
                    ]
                )
            );
        }
    }

    /**
     * Создание записи истории при обновлении задачи
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $task = $args->getObject();

        if (!$task instanceof Task) {
            return;
        }

        $user = $this->security->getUser();

        $unitOfWork = $args->getObjectManager()->getUnitOfWork();
        $changes = $unitOfWork->getEntityChangeSet($task);

        $updateChanges = [];

        foreach ($changes as $field => [$old, $new]) {

            if (in_array($field, ['title', 'description'])) {
                $updateChanges[$field] = $this->normalizeChange($field, $old, $new);
                continue;
            }

            $normalized = $this->normalizeChange($field, $old, $new);

            $this->historyRepository->save(
                new TaskHistory(
                    task: $task,
                    action: self::ACTION_MAP[$field],
                    performedBy: $user,
                    changes: [$field => $normalized]
                )
            );
        }
        if (!empty($updateChanges)) {
            $this->historyRepository->save(
                new TaskHistory(
                    task: $task,
                    action: 'update',
                    performedBy: $user,
                    changes: $updateChanges
                )
            );
        }
    }

    private function normalizeChange(string $field, $old, $new): array
    {
        return match ($field) {
            'assignee' => [
                'old' => $this->normalizeField($field, $old),
                'new' => $this->normalizeField($field, $new)
            ],
            'dueDate' => [
                'old' => $this->normalizeField($field, $old),
                'new' => $this->normalizeField($field, $new)
            ],
            'priority' => [
                'old' => $this->normalizeField($field, $old),
                'new' => $this->normalizeField($field, $new)
            ],
            default => ['old' => $old, 'new' => $new],
        };
    }

    private function normalizeField(string $field, $value): mixed
    {
        return match ($field) {
            'assignee' => $value?->getName()->getFullName(),
            'dueDate'  => $value?->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            'priority' => $this->getPriorityLabelSafe($value),
            default    => $value,
        };
    }

    private function getPriorityLabelSafe(int $priorityValue): string
    {
        try {
            return new TaskPriority($priorityValue)->getLabel();
        } catch (\Throwable $e) {
            return 'unknown';
        }
    }
}
