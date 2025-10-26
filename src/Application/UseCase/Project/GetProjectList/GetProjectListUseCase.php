<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\GetProjectList;

use Kamalo\KanbanTaskManagementSystem\Application\Exception\AccessDeniedException;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GetProjectListUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private ProjectRepositoryInterface $repository
    ) {}

    public function __invoke(): GetProjectListResponse
    {
        $projects = $this->repository->find();

        // TODO А если нет проектов
        // if (!$this->auth->isGranted('VIEW', $projects[0])) {
        //     throw new AccessDeniedException();
        // }
        $responseArray = [];
        foreach ($projects as $key => $project) {
            $responseArray[$key]['id'] = $project->getId();
            $responseArray[$key]['title'] = $project->getTitle()->getValue();
            $responseArray[$key]['description'] = $project->getDescription()?->getValue();
            $responseArray[$key]['ownerId'] = $project->getOwner()->getId();
            $responseArray[$key]['isActive'] = $project->getIsActive();
        }

        return new GetProjectListResponse($responseArray);
    }
}
