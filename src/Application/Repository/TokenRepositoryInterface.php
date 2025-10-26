<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Repository;

interface TokenRepositoryInterface
{
    public function get(string $key): string;

    public function set(string $key, $value, ?int $ttl = null): void;

    public function delete(string $key): void;
}
