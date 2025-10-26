<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectProgress;

class ComputeProjectProgressResponse
{
    public function __construct(
        public readonly float $progress,
        public readonly int $allTasksCount,
        public readonly int $completedTasksCount
    ) {}
}
