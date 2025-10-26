<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskHistory;

class GetTaskHistoryRequest
{
    public function __construct(
        public readonly int $id
    ) {}
}
