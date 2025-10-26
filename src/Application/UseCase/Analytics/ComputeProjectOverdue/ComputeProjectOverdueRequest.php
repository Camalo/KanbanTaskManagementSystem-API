<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectOverdue;

class ComputeProjectOverdueRequest
{
    public function __construct(
        public readonly int $projectId
    ) {}
}
