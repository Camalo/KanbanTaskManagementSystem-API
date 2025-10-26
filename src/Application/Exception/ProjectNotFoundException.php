<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class ProjectNotFoundException extends \RuntimeException
{
    public function __construct(int $projectId)
    {
        parent::__construct("Проект с ID $projectId не найден.");
    }
}