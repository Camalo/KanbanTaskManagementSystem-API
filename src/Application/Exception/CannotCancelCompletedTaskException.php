<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class CannotCancelCompletedTaskException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Невозможно отменить задачу: задача уже завершена.");
    }
}