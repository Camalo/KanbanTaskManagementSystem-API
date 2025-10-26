<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\DeleteProject;

class DeleteProjectRequest
{
    public function __construct(
        public readonly int $id
    ) {}
}
