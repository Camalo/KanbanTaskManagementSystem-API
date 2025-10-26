<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\CreateProject;

class CreateProjectRequest{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description
    ) {}
}