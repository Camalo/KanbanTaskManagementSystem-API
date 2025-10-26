<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\SearchTask;

use Kamalo\KanbanTaskManagementSystem\Domain\Repository\SearchRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SearchTaskUseCase
{
    public function __construct(
        private Security $security,
        private AuthorizationCheckerInterface $auth,
        private SearchRepositoryInterface $repository
    ) {}

    public function __invoke(SearchTaskRequest $request): SearchTaskResponse
    {
        // Тут как будто не нужен user и проверка прав доступа?
        // TODO вынести из каждого useCase этот блок кода
        // $user = $this->security->getUser();

        // if (!$this->auth->isGranted('VIEW', $task)) {
        //     throw new AccessDeniedException('Нет прав для добавления участника');
        // }

        $items = $this->repository->search($request->query);
        return new SearchTaskResponse($items);
    }
}
