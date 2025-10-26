<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets;

class CreateUserFieldSets
{
    public const REQUIRED = [
        'email',
        'password',
        'firstName',
        'lastName',
        'timezone'
    ];

    public const OPTIONAL = [
        'middleName'
    ];
}
