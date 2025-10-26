<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectPerformance;

class ComputeProjectPerformanceRequest{
    public function __construct(
        public readonly int $projectId
    ) {}
}