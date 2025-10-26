<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\SearchTask;

class SearchTaskResponse
{
    public function __construct(
        public readonly array $items
    ) {}
}
