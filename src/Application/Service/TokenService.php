<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Service;

use Doctrine\ORM\EntityManagerInterface;
use Kamalo\KanbanTaskManagementSystem\Application\Repository\TokenRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Kamalo\KanbanTaskManagementSystem\Domain\Security\TokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenService
{
    private const TTL = 604800; // время жизни refresh токена в секундах

    // TODO:: Переименовать LoginAttempsRepositoryInterface и LoginAttempsRepository
    public function __construct(
        private TokenRepositoryInterface $tokenRepository,
        private JWTTokenManagerInterface $jwtManager,
        private UserProviderInterface $userProvider,
        private EntityManagerInterface $em,
    ) {}

    /** @return array{access_token:string, refresh_token:string, expires_in:int} */
    public function refreshForUser(User $user, string $refreshToken): array
    {
        //Сервер валидирует refresh token (проверяет в БД/черном списке, TTL, single-use и т.п.), 
        // и выдаёт новый access token (и часто — новый refresh token при ротации).

        $userId = $this->tokenRepository->get("refresh_token:$refreshToken");
        // TODO:: Если не нашел по ключу - исключение
        // TODO:: Если id пользователя не совпадает  - исключение

        // удалим старый токен, чтобы токен был одноразовым
        $this->deleteRefreshToken($refreshToken);

        return $this->generateForUser($user);
    }

    /** @return array{access_token:string, refresh_token:string, expires_in:int} */
    public function generateForUser(User $user): array
    {
        $accessToken = $this->generateAccessTokenForUser($user);
        $refreshToken = $this->generateRefreshToken();

        $this->tokenRepository->set(
            key: "refresh_token:$refreshToken",
            value: $user->getId(),
            ttl: self::TTL
        );

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'expiresIn' => intval($_ENV['JWT_TTL']),
        ];
    }

    public function deleteRefreshToken(string $refreshToken): void
    {
        $this->tokenRepository->delete("refresh_token:$refreshToken");
    }
    private function generateAccessTokenForUser(User $user): string
    {
        return $this->jwtManager->create($user);
    }

    private function generateRefreshToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
