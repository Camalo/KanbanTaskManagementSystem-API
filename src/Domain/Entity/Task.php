<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Entity;

use DateTimeImmutable;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Embedded(class: Title::class, columnPrefix: false)]
    private Title $title;

    #[ORM\Embedded(class: Description::class, columnPrefix: false)]
    private ?Description $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $owner;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $assignee = null;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Project $project;

    #[ORM\Column(type: 'smallint')]
    private int $priority;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dueDate = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $completedAt = null;

    public function __construct(
        Title $title,
        User $owner,
        ?int $id,
        ?Description $description,
        ?User $assignee,
        ?DateTimeImmutable $dueDate
    ) {
        $this->id = $id;

        if ($this->id === null) {
            $this->assertDueDate($dueDate);
        }

        $this->title = $title;
        $this->description = $description;
        $this->owner = $owner;
        $this->assignee = $assignee;
    }

    /**
     * Получить ID задачи.
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получить заголовок задачи.
     * 
     * @return Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * Установить или обновить заголовок задачи.
     * 
     * @param Title $title
     * 
     * @return self
     */
    public function updateTitle(Title $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Получить описание задачи
     * 
     * @return Description|null
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    /**
     * Установить или обновить описание задачи
     * 
     * @param Description $description
     * 
     * @return self
     */
    public function updateDescription(Description $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getAssignee(): User
    {
        return $this->assignee;
    }

    public function updateAssignee(User $assignee): self
    {
        $this->assignee = $assignee;
        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function updateProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function updatePriority(TaskPriority $priority): self
    {
        $this->priority = $priority->getValue();
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function updateStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function updateDueDate(DateTimeImmutable $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function markAsCreated(): self
    {
        // TODO:: Выбросить исключение если createdAt !== null
        $this->createdAt = new DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): self
    {
        // TODO:: Выбросить исключение если updatedAt !== null
        $this->updatedAt = new DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
        return $this;
    }

    public function getCompletedAt(): DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function markAsCompleted(): self
    {
        // TODO:: Выбросить исключение если completedAt !== null
        $this->completedAt = new DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
        return $this;
    }

    private function assertDueDate(DateTimeImmutable $dueDate): void
    {
        // TODO:: проверить чтобы dueDate была в будущем
    }
}
