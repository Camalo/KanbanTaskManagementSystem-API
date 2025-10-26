<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Repository;

use Kamalo\KanbanTaskManagementSystem\Application\Repository\TokenRepositoryInterface;

class RedisTokenRepository implements TokenRepositoryInterface
{
    private const KEY_PREFIX = 'refresh_token:';
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        try {
            $this->redis->connect(
                $_ENV['REDIS_HOST'],
                (int) $_ENV['REDIS_PORT']
            );
        } catch (\Throwable $e) {
            // print_r($e->getTrace());
            throw new \Exception('Ошибка при подключении к Redis: ' . $e->getMessage());
        }
    }

    public function get(string $key): string
    {
        $key = self::KEY_PREFIX . "$key";
        return $this->redis->get($key);
    }

    public function set(string $key, $value, ?int $ttl = null): void
    {
        $key = self::KEY_PREFIX . "$key";
        $this->redis->set($key, $value);

        if ($ttl !== null) {
            $this->redis->expire($key, $ttl);
        }
    }

    public function delete(string $key): void
    {
        $key = self::KEY_PREFIX . "$key";
        $this->redis->del($key);
    }
}
