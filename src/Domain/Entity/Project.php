<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kamalo\KanbanTaskManagementSystem\Domain\Collection\UserCollection;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'projects')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Embedded(class: Title::class, columnPrefix: false)]
    private Title $title;

    #[ORM\Embedded(class: Description::class, columnPrefix: false)]
    private Description $description;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $owner;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'projects')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function updateIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addProject($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeProject($this);
        }

        return $this;
    }
}
