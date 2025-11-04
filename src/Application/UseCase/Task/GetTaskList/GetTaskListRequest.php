<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskList;

class GetTaskListRequest
{
    public function __construct(
        public readonly int $projectId,
        public readonly bool $showCanceled
    ) {}
}
