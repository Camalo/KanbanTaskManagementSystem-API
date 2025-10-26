<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Analytics\ComputeProjectOverdue;

class ComputeProjectOverdueResponse
{
    public function __construct(
        public readonly int $total,
        public readonly array $ids
    ) {}
}
