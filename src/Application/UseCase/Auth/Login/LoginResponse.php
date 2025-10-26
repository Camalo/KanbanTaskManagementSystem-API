<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\Login;

class LoginResponse
{
    public function __construct(
        readonly public string $accessToken,
        readonly public string $refreshToken,
        readonly public int $expiresIn
    ) {}
}
