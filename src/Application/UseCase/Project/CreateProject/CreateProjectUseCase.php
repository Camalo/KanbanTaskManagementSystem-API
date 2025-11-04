<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\CreateProject;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CreateProjectUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function __invoke(CreateProjectRequest $request): CreateProjectResponse
    {
        // TODO вынести из каждого useCase этот блок кода
        $user = $this->security->getUser();

        $description  = null;
        if ($request->description !== null) {
            $description = new Description($request->description);
        }

        $project = new Project(
            id: null,
            title: new Title($request->title),
            description: $description,
            owner: $user
        );

        if (!$this->auth->isGranted('PROJECT_CREATE', $project)) {
            throw new AccessDeniedException();
        }

        $this->projectRepository->save($project);

        return new CreateProjectResponse($project->getId());
    }
}
