<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Symfony\Component\Security\Core\Security;

#[AsDoctrineListener(event: Events::preUpdate)]
class TaskHistoryListener
{
    // TODO:: Реализовать логирование истории изменения задач
    // public function __construct(
    //     private readonly Security $security // чтобы знать, кто сделал изменение
    // ) {}

    // public function preUpdate(PreUpdateEventArgs $args): void
    // {
    //     $entity = $args->getObject();

    //     // интересует только Task
    //     if (!$entity instanceof Task) {
    //         return;
    //     }

    //     $em = $args->getObjectManager();
    //     $changes = $args->getEntityChangeSet();

    //     foreach ($changes as $field => [$oldValue, $newValue]) {
    //         $history = new TaskHistory();
    //         $history->setTask($entity);
    //         $history->setChangedBy($this->security->getUser());
    //         $history->setFieldName($field);
    //         $history->setOldValue(is_object($oldValue) ? (string)$oldValue : (string)$oldValue);
    //         $history->setNewValue(is_object($newValue) ? (string)$newValue : (string)$newValue);
    //         $history->setChangedAt(new \DateTimeImmutable());

    //         $em->persist($history);
    //     }
    // }
}