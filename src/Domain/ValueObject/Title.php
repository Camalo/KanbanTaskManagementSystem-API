<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\ValueObject;

use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Title
{
    #[ORM\Column(type: 'string', name: 'title', length: 255)]
    private string $value;

    public function __construct(string $value)
    {
        $this->assert($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assert(string $value): void
    {
        if (mb_strlen(trim($value)) < 1) {
            throw new InvalidArgumentException('Заголовок не должен быть пустым');
        }

        if (mb_strlen(trim($value)) > 255) {
            throw new InvalidArgumentException('Заголовок должен иметь длину не более 255 символов.');
        }
    }
}
