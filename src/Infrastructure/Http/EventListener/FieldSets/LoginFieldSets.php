<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets;

class LoginFieldSets
{
    public const REQUIRED = [
        'email',
        'password'
    ];
}
