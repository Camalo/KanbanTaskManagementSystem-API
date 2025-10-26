<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\UserRepositoryInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
    }
    /**
     * Найти пользователя по id
     * @param int $id
     */
    public function findById(int $id): ?User
    {
        return $this->repository->find($id);
    }

    /**
     * Найти пользователя по email
     * @param string $email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    /**
     * Найти пользователей - возможно не нужно вообще, но сделано как и для tasks
     * 
     * @param array $filters
     */
    public function find(array $filters): array
    {
        return [];
    }

    /**
     * Создать или изменить пользователя
     * 
     * @param \App\Domain\Entity\User $user
     * 
     * @return void
     */
    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Удалить пользователя
     * 
     * @param \App\Domain\Entity\User $user
     * 
     * @return void
     */
    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
