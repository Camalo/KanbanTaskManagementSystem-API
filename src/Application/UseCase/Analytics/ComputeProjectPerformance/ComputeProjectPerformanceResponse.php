<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectPerformance;

class ComputeProjectPerformanceResponse
{
    public function __construct(
        public readonly float $totalSeconds,
        public readonly int $seconds,
        public readonly int $minutes,
        public readonly int $hours,
        public readonly int $days
    ) {}
}
