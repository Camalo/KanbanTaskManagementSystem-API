<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\GetProjectList;

class GetProjectListResponse
{
    public function __construct(
        public readonly array $items
    ) {}
}
