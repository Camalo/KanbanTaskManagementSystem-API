<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\ValueObject;

use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Description
{
    #[ORM\Column(type: 'text', name: 'description', nullable: true)]
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
        if (mb_strlen(trim($value)) > 2000) {
            throw new InvalidArgumentException('Описание должно иметь длину не более 2000 символов.');
        }
    }
}
