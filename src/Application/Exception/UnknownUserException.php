<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class UnknownUserException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Невозможно определить пользователя или его тип.');
    }
}