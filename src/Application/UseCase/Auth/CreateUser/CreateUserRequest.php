<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\CreateUser;

class CreateUserRequest
{
    public function __construct(
        public readonly string $firstName,
        public readonly ?string $middleName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $password,
        public readonly string $timezone
    ) {}
}
