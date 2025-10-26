<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class InvalidCredentialsException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Неверный email или пароль.');
    }
}