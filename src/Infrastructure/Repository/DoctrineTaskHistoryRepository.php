<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\TaskHistory;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskHistoryRepositoryInterface;


class DoctrineTaskHistoryRepository implements TaskHistoryRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(TaskHistory::class);
    }

    /**
     * Возвращает историю задачи
     * 
     * @param Task $task
     * 
     * @return TaskHistory[]
     */
    public function findByTask(Task $task): array
    {
        return $this->repository->createQueryBuilder('h')
            ->andWhere('h.task = :task')
            ->setParameter('task', $task)
            ->orderBy('h.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Создать или изменить элемент истории задачи
     * 
     * @param \App\Domain\Entity\TaskHistory $history
     * 
     * @return void
     */
    public function save(TaskHistory $history): void
    {
        $this->em->persist($history);
        $this->em->flush();
    }
}
