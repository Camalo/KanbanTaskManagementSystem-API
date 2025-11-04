<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class CannotUpdateStatusWithoutAssigneeException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Невозможно обновить статус задачи: задаче не назначен исполнитель.");
    }
}