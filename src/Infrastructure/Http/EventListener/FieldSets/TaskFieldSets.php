<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets;

class TaskFieldSets
{
    public const REQUIRED = [
        'title'
    ];

    public const OPTIONAL = [
        'description',
        'assignee',
        'dueDate',
        'priority'
    ];
}
