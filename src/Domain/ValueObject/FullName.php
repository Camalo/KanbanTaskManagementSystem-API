<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class FullName
{
    #[ORM\Column(type: 'string', length: 100)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $middleName;

    #[ORM\Column(type: 'string', length: 100)]
    private string $lastName;

    public function __construct(
        string $firstName,
        string $lastName,
        ?string $middleName = null,
    ) {
        $this->assert($firstName);
        $this->assert($lastName);

        if ($middleName !== null) {
            $this->assert($middleName);
        }

        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }

    /**
     * Получить имя пользователя
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Получить отчество пользователя
     *
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * Получить фамилию пользователя
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Получить полное имя пользователя (Фамилия + имя + отчество)
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->lastName . ' ' . $this->firstName . ($this->middleName ? ' ' . $this->middleName : '');
    }

    private function assert(string $value)
    {
        if ($value !== trim($value)) {
            throw new \InvalidArgumentException('Имя не должно содержать пробелы в начале/конце');
        }

        if (mb_strlen($value) < 1) {
            throw new \InvalidArgumentException('Имя должно содержать не менее 1 символа');
        }

        if (mb_strlen($value) > 100) {
            throw new \InvalidArgumentException('Имя должно иметь длину не более 100 символов');
        }
    }
}
