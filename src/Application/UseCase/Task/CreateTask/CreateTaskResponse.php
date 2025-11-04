<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\CreateTask;

class CreateTaskResponse
{
    public function __construct(
        public readonly int $id
    ) {}
}
