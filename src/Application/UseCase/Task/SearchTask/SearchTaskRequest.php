<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\SearchTask;

class SearchTaskRequest
{
    public function __construct(
        public readonly string $query
    ) {}
}
