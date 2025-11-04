<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Auth\CreateUser;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\NonUniqueUserException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\UserRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\FullName;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserUseCase
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $repository
    ) {}

    public function __invoke(CreateUserRequest $request): CreateUserResponse
    {
        if ($this->repository->findByEmail($request->email)) {
            throw new NonUniqueUserException($request->email);
        }

        $user = new User(
            id: null,
            email: $request->email,
            name: new FullName(
                firstName: $request->firstName,
                middleName: $request->middleName,
                lastName: $request->lastName
            ),
            roles: ['ROLE_USER'],
            isActive: true
        );

        $user->updateTimezone($request->timezone);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $request->password);
        $user->updatePassword($hashedPassword);


        $this->repository->save($user);

        return new CreateUserResponse(
            $user->getId(),
            'Пользователь с id ' . $user->getId() . ' успешно создан'
        );
    }
}
