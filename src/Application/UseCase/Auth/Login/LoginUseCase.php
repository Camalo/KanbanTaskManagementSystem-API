<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\Login;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\InvalidCredentialsException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\LoginAttemptsExceededException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\UnknownUserException;
use Kamalo\KanbanTaskManagementSystem\Application\Service\RateLimiter\RateLimiterInterface;
use Kamalo\KanbanTaskManagementSystem\Application\Service\TokenService;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LoginUseCase
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
        private UserRepositoryInterface $userRepository,
        private TokenService $tokenService,
        private RateLimiterInterface $rateLimiter
    ) {}

    public function __invoke(LoginRequest $request): LoginResponse
    {
        if (!$this->rateLimiter->consume($request->ip)) {
            throw new LoginAttemptsExceededException();
        }

        if (!$user = $this->userRepository->findByEmail($request->email)) {
            throw new UnknownUserException();
        }

        if (!$this->passwordHasher->isPasswordValid(
            user: $user,
            plainPassword: $request->password
        )) {
            throw new InvalidCredentialsException();
        }

        $this->rateLimiter->reset($request->ip);

        $tokens = $this->tokenService->generateForUser($user);

        return new LoginResponse(
            $tokens['accessToken'],
            $tokens['refreshToken'],
            $tokens['expiresIn']
        );
    }
}
