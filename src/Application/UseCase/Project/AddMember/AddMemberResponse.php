<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\AddMember;

class AddMemberResponse
{
    public function __construct(
        public readonly int $projectId,
        public readonly string $memberName
    ) {}
}
