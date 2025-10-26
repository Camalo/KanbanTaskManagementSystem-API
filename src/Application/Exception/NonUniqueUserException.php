<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class NonUniqueUserException extends \RuntimeException
{
    public function __construct(string $email)
    {
        parent::__construct("Пользователь с email $email уже существует.");
    }
}