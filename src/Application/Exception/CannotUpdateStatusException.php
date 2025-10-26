<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class CannotUpdateStatusException extends \RuntimeException
{
    public function __construct(string $fromStatus, string $transition)
    {
        parent::__construct("Невозможно обновить статус задачи: $fromStatus не поддерживает $transition.");
    }
}