<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskDetail;

class GetTaskDetailResponse
{
    public function __construct(
        public readonly int $id,
        public readonly int $projectId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly int $owner,
        public readonly int $assignee,
        public readonly string $priority,
        public readonly string $status,
        public readonly ?string $dueDate,
        public readonly string $createdAt,
        public readonly ?string $updatedAt,
        public readonly ?string $completedAt
    ) {}
}
