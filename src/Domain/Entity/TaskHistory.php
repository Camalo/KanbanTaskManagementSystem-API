<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'tasks_history')]
class TaskHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Task $task;

    #[ORM\Column(type: 'string', length: 32)]
    private string $action;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $changes = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $performedBy = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    private const ACTIONS = [
        'create',
        'assign',
        'update',
        'change_status',
        'change_priority',
        'change_due_date'
    ];

    public function __construct(
        Task $task,
        string $action,
        array $changes,
        ?User $performedBy
    ) {
        $this->task = $task;

        if(!in_array($action, self::ACTIONS)){
            throw new \InvalidArgumentException("Неверно указанное действие: $action");
        }

        $this->action = $action;
        $this->changes = $changes;
        $this->performedBy = $performedBy;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getPerformedBy(): ?User
    {
        return $this->performedBy;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
    }
}
