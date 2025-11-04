<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\CreateProject;

class CreateProjectResponse{
    public function __construct(
        public readonly int $id
    ) {}
}