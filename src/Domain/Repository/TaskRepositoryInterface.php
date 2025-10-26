<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Repository;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;

interface TaskRepositoryInterface
{
    /**
     * Найти задачу по id
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id): ?Task;

    /**
     * Возвращает список задач.
     * 
     * @param array $filters  e.g. ['status' => TaskStatus::OPEN->value, 'ownerId' => 5]
     * 
     * @return array
     */
    public function find(
        array $filters
    ): array;

    /**
     * Возвращает список задач.
     * 
     * @param array $fields   e.g. ['id', 'title', 'dueDate']
     * @param array $filters  e.g. ['status' => TaskStatus::OPEN->value, 'dueDate' => ['operator' => '>', 'value' => $dueDate]]
     * 
     * @return array
     */
    public function findScalar(
        array $fields,
        array $filters
    ): array;
    
    /**
     * Создать или изменить задачу
     * 
     * @param \App\Domain\Entity\Task $task
     * 
     * @return void
     */
    public function save(Task $task): void;

    /**
     * Удалить задачу
     * 
     * @param \App\Domain\Entity\Task $task
     * 
     * @return void
     */
    public function delete(Task $task): void;

    /**
     * Посчитать общее количество элементов
     * 
     * @return int
     */
    public function count(array $filters): int;

    public function findAverageCompletionTime(Project $project): float;
}
