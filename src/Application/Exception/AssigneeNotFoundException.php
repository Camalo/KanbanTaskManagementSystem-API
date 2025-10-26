<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class AssigneeNotFoundException extends \RuntimeException
{
    public function __construct(int $userId)
    {
        parent::__construct("Исполнитель с id $userId не найден.");
    }
}