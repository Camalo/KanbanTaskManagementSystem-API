<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\Login;

class LoginRequest
{
    public function __construct(
        readonly public string $ip,
        readonly public string $email,
        readonly public string $password
    ) {}
}
