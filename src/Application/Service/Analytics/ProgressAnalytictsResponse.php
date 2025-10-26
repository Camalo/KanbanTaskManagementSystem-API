<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics;

class ProgressAnalytictsResponse
{
    public function __construct(
        public readonly float $progress,
        public readonly int $allTasksCount,
        public readonly int $completedTasksCount
    ) {}
}
