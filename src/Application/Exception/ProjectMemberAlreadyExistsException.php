<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class ProjectMemberAlreadyExistsException extends \RuntimeException
{
    public function __construct(int $userId)
    {
        parent::__construct("Участник c id $userId уже в проекте. Невозможно добавить его еще раз.");
    }
}