<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Exception;

class LoginAttemptsExceededException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Превышено количество попыток входа. Попробуйте позже.');
    }
}