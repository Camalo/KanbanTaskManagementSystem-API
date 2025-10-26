<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics;

class PerfomanceAnalyticsResponse
{
    public function __construct(
        public readonly float $total,
        public readonly int $days,
        public readonly int $hours,
        public readonly int $minutes,
        public readonly int $seconds,
    ) {}
}
