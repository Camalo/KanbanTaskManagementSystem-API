<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics;

class OverdueAnalyticsResponse
{
    public function __construct(
        public readonly int $total,
        public readonly array $ids
    ) {}
}
