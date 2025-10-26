<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\ProjectRepositoryInterface;

class DoctrineProjectRepository implements ProjectRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Project::class);
    }
    /**
     * Найти проект по id
     * 
     * @param int $id
     * 
     * @return Project|null
     */
    public function findById(int $id): ?Project
    {
        return $this->repository->find($id);
    }

    /**
     * Возвращает список проектов.
     * 
     * @return array
     */
    public function find(): array{
        return $this->repository->findAll();
    }

    /**
     * Создать или изменить проект
     * 
     * @param \App\Domain\Entity\Project $project
     * 
     * @return void
     */
    public function save(Project $project): void
    {
        $this->em->persist($project);
        $this->em->flush();
    }

    /**
     * Удалить проект
     * 
     * @param \App\Domain\Entity\Project $project
     * 
     * @return void
     */
    public function delete(Project $project): void
    {
        $this->em->remove($project);
        $this->em->flush();
    }
}
