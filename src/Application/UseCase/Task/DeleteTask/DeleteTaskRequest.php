<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\DeleteTask;

class DeleteTaskRequest
{
    public function __construct(
        public readonly int $id
    ) {}
}
