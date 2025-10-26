<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\AddMember;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectMemberAlreadyExistsException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\ProjectNotFoundException;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\UnknownUserException;
use Symfony\Bundle\SecurityBundle\Security;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AddMemberUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $projectRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(AddMemberRequest $request): void
    {
        $user = $this->security->getUser();
        $project = $this->projectRepository->findById($request->projectId);

        if (!$project) {
            throw new ProjectNotFoundException($request->projectId);
        }
        
        if (!$this->auth->isGranted('PROJECT_MANAGE', $project)) {
            throw new AccessDeniedException();
        }

        $member = $this->userRepository->findById($request->userId);

        if (!$member) {
            throw new UnknownUserException();
        }

        // Пользователь не должен уже быть участником проекта.
        if ($project->hasMember($member)) {
            throw new ProjectMemberAlreadyExistsException($member->getId());
        }

        $project->addMember($member);

        $this->projectRepository->save($project);

        $this->userRepository->save($user);
    }
}
