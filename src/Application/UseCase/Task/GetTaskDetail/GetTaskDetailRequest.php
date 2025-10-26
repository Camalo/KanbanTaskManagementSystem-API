<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskDetail;

class GetTaskDetailRequest
{
    public function __construct(
        public readonly int $id,
        public readonly int $projectId
    ) {}
}
