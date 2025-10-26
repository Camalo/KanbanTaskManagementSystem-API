<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\SearchRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::postPersist, priority: 0)]
#[AsDoctrineListener(event: Events::postUpdate, priority: 0)]
class TaskElasticListener{

     public function __construct(
        private Security $security,
        private SearchRepositoryInterface $elasticRepository
    ) {}


    public function postPersist(PostPersistEventArgs $args): void
    {
        $task = $args->getObject();

        if (!$task instanceof Task) {
            return;
        }

        $this->elasticRepository->save($task);
    }

    /**
     * Создание записи истории при обновлении задачи
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $task = $args->getObject();

        if (!$task instanceof Task) {
            return;
        }

        $this->elasticRepository->save($task);
    }

}