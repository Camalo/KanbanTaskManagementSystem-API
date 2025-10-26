<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Repository;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;

interface ProjectRepositoryInterface
{
    /**
     * Найти задачу по id
     * @param int $id
     * @return Project|null
     */
    public function findById(int $id): ?Project;

    /**
     * Возвращает список задач с пагинацией и базовыми фильтрами.
     * 
     * @return array
     */
    public function find(): array;
    
    /**
     * Создать или изменить задачу
     * 
     * @param \App\Domain\Entity\Project $project
     * 
     * @return void
     */
    public function save(Project $project): void;

    /**
     * Удалить задачу
     * 
     * @param \App\Domain\Entity\Project $project
     * 
     * @return void
     */
    public function delete(Project $project): void;
}
