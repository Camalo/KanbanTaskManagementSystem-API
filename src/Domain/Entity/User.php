<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\FullName;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const ALLOWED_ROLES = [
        'ROLE_USER',
        'ROLE_MANAGER'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Ignore]
    private string $password;

    #[ORM\Embedded(class: FullName::class)]
    private FullName $name;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'participants')]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Установить или обновить email пользователя
     * 
     * @param string @email
     * 
     * @throws InvalidArgumentException если email не соответствует формату или имеет несуществующий домен
     */
    public function updateEmail(string $email): void
    {
        $email = strtolower(trim($email));

        if (!filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )) {
            throw new InvalidArgumentException('Invalid value "email": value must be an correct email');
        }

        $domain =
            substr(
                strrchr(
                    $email,
                    "@"
                ),
                1
            );

        if (!getmxrr(
            $domain,
            $mxhosts
        )) {
            throw new InvalidArgumentException('Invalid value "email": value must have an correct domain');
        }

        $this->email = $email;
    }

    /**
     * Получить хэш пароля пользователя
     * 
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Установить или обновить пароль пользователя
     * 
     * @param string $passwordHash
     * 
     * @throws InvalidArgumentException если пароль не является хэшем
     */
    public function updatePassword(string $passwordHash): void
    {
        if (!str_starts_with($passwordHash, '$2y$')) {
            throw new InvalidArgumentException('Password must be a bcrypt hash');
        }
        $this->password = $passwordHash;
    }

    /**
     * Получить имя пользователя
     * 
     * @return App\Domain\ValueObject\FullName
     */
    public function getName(): FullName
    {
        return $this->name;
    }

    // public function getProjects(): Collection{
    //     return $this->projects;
    // }
    
    /**
     * Получить роли пользователей
     * 
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Установить или обновить роли пользователя
     * 
     * @param array
     */
    public function updateRoles(array $roles): void
    {
        if ($roles === []) {
            $this->roles = ['ROLE_USER'];
        } else if (in_array(
            needle: current($roles),
            haystack: self::ALLOWED_ROLES
        )) {
            $this->roles = $roles;
        } else {
            throw new InvalidArgumentException('Invalid role');
        }
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
     * Получить email как идентификатор
     * 
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void {}

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        $this->projects->removeElement($project);

        return $this;
    }
}
