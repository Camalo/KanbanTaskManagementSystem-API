<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\ValueObject;

use InvalidArgumentException;

class TaskPriority
{
    private const WHITELIST = [
        1,
        2,
        3
    ];

    const array KEYS = [
        1 => "low",
        2 => "normal",
        3 => "high"
    ];

    const array LABELS = [
        1 => "Низкий",
        2 => "Нормальный",
        3 => "Высокий"
    ];

    private int $value;

    public function __construct(int $value)
    {
        $this->assert($value);

        $this->value = $value;
    }

    public function getKey(): string
    {
        return self::KEYS[$this->value];
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return self::LABELS[$this->value];
    }

    private function assert(int $value): void
    {
        if (!in_array($value, self::WHITELIST)) {
            throw new InvalidArgumentException("Некорретное значение приоритета задачи");
        }
    }
}
