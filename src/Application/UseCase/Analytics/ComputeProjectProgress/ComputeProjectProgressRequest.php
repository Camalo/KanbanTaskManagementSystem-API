<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectProgress;

class ComputeProjectProgressRequest
{
    public function __construct(
        public readonly int $projectId
    ) {}
}
