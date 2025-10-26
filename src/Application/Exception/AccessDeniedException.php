<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class AccessDeniedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Пользователь не имеет прав для данного действия.');
    }
}