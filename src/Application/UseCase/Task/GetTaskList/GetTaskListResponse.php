<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskList;

class GetTaskListResponse
{
    public function __construct(
        public readonly array $items
    ) {}
}
