<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Service\RateLimiter;

interface RateLimiterInterface
{
    public function consume(string $key): bool;
    public function reset(string $key): void;
}