<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Repository;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;

interface UserRepositoryInterface
{
    /**
     * Найти пользователя по id
     * @param int $id
     */
    public function findById(int $id): ?User;

    /**
     * Найти пользователя по email
     * @param string $email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Найти пользователей - возможно не нужно вообще, но сделано как и для tasks
     * 
     * @param array $filters
     */
    public function find(array $filters): array;

    /**
     * Создать или изменить пользователя
     * 
     * @param \App\Domain\Entity\User $user
     * 
     * @return void
     */
    public function save(User $user): void;

    /**
     * Удалить пользователя
     * 
     * @param \App\Domain\Entity\User $user
     * 
     * @return void
     */
    public function delete(User $user): void;
}
