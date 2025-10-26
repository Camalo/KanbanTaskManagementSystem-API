<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Domain\Repository;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;

interface SearchRepositoryInterface{

    public function search(string $query): array;

    public function save(Task $task);
}