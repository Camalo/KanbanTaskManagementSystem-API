<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Repository;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\TaskHistory;

interface TaskHistoryRepositoryInterface
{
    /**
     * Возвращает историю задачи
     * 
     * @param Task $task
     * 
     * @return TaskHistory[]
     */
    public function findByTask(Task $task): array;

    /**
     * Создать или изменить элемент истории задачи
     * 
     * @param \App\Domain\Entity\TaskHistory $history
     * 
     * @return void
     */
    public function save(TaskHistory $history): void;
}
