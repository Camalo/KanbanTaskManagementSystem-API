<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class AssigneeNotInProjectException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Невозможно прикрепить исполнителя к задаче, исполнитель не состоит в проекте.');
    }
}