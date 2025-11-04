<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\CreateUser;

class CreateUserResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $message
    ) {}
}
