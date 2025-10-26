<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\UpdateTaskStatus;

class UpdateTaskStatusRequest{
     public function __construct(
        public readonly int $id,
        public readonly ?string $transition
    ) {}
}