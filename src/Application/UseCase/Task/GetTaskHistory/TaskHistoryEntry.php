<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskHistory;

class TaskHistoryEntry
{
    public function __construct(
        public string $action,
        public ?array $changes,
        public ?int $performedById,
        public string $performedByName,
        public ?string $createdAt
    ) {}
}
