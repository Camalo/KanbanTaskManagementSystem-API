<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ValidateJson
{
    /**
     * @param string[] $requiredFields  Обязательные поля
     * @param string[] $optionalFields  Необязательные (разрешённые) поля
     */
    public function __construct(
        public array $requiredFields = [],
        public array $optionalFields = [],
    ) {}
}
