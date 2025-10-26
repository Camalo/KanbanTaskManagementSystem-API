<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Service\RateLimiter;

use Kamalo\KanbanTaskManagementSystem\Application\Service\RateLimiter\RateLimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class SymfonyRateLimiterAdapter implements RateLimiterInterface
{
    public function __construct(
        private RateLimiterFactory $loginLimiter
    ) {}

    public function consume(string $key): bool
    {
        $limit = $this->loginLimiter->create($key)->consume(1);
        return $limit->isAccepted();
    }

    public function reset(string $key): void
    {
        $this->loginLimiter->create($key)->reset();
    }
}
