<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\CreateTask;

class CreateTaskRequest
{
    public function __construct(
        public readonly string $title,
        public readonly int $projectId,
        public readonly ?string $description,
        public readonly ?int $assignee,
        public readonly ?string $priority,
        public readonly ?string $dueDate
    ) {}
}
