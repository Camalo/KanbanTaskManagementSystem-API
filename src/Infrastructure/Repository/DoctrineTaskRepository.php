<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;

class DoctrineTaskRepository implements TaskRepositoryInterface
{
    private const FIELDS = [
        'id',
        'title',
        'description',
        'owner',
        'assignee',
        'project',
        'priority',
        'status',
        'dueDate',
        'createdAt',
        'updatedAt',
        'completedAt'
    ];

    private const OPERATORS = [
        '=',
        '!=',
        '<',
        '>',
        '<=',
        '>='
    ];

    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Task::class);
    }
    /**
     * Найти задачу по id
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id): ?Task
    {
        return $this->repository->find($id);
    }

    /**
     * Возвращает список задач.
     * 
     * @param array $filters  e.g. ['status' => TaskStatus::OPEN->value, 'dueDate' => ['operator' => '>', 'value' => $dueDate]]
     * 
     * @return array
     */
    public function find(
        array $filters
    ): array {

        $tasksBuilder = $this->repository->createQueryBuilder('t');

        foreach ($filters as $field => $condition) {

            if (!in_array($field, self::FIELDS)) {
                throw new \InvalidArgumentException("Недопустимый оператор 1: $field");
            }

            $operator = (is_array($condition) && isset($condition['operator'])) ? $condition['operator'] : '=';

            if (!in_array($operator, self::OPERATORS, true)) {
                throw new \InvalidArgumentException("Недопустимый оператор 2: $operator");
            }

            $value = (is_array($condition) && isset($condition['value'])) ? $condition['value'] : $condition;

            $tasksBuilder->andWhere(sprintf('t.%s %s :%s', $field, $operator, $field))
                ->setParameter($field, $value);
        }

        return $tasksBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * Возвращает список задач.
     * 
     * @param array $fields   e.g. ['id', 'title', 'dueDate']
     * @param array $filters  e.g. ['status' => TaskStatus::OPEN->value, 'dueDate' => ['operator' => '>', 'value' => $dueDate]]
     * 
     * @return array
     */
    public function findScalar(
        array $fields,
        array $filters
    ): array {

        $tasksBuilder = $this->repository->createQueryBuilder('t');

        $selectFields = [];

        foreach ($fields as $field) {
            if (!in_array($field, self::FIELDS, true)) {
                throw new \InvalidArgumentException(sprintf('Field "%s" is not allowed.', $field));
            }
            $selectFields[] = 't.' . $field . ' AS ' . $field;
        }

        $tasksBuilder->select(implode(', ', $selectFields));

        foreach ($filters as $field => $condition) {

            if (!in_array($field, self::FIELDS, true)) {
                throw new \InvalidArgumentException("Недопустимый оператор 3: $field");
            }

            $operator = (is_array($condition) && isset($condition['operator'])) ? $condition['operator'] : '=';

            if (!in_array($operator, self::OPERATORS, true)) {
                throw new \InvalidArgumentException("Недопустимый оператор 4: $operator");
            }

            $value = (is_array($condition) && isset($condition['value'])) ? $condition['value'] : $condition;

            $tasksBuilder->andWhere(sprintf('t.%s %s :%s', $field, $operator, $field))
                ->setParameter($field, $value);
        }

        return $tasksBuilder
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Создать или изменить задачу
     * 
     * @param \App\Domain\Entity\Task $task
     * 
     * @return void
     */
    public function save(Task $task): void
    {
        $this->em->persist($task);
        $this->em->flush();
    }

    /**
     * Удалить задачу
     * 
     * @param \App\Domain\Entity\Task $task
     * 
     * @return void
     */
    public function delete(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }

    /**
     * Посчитать общее количество элементов
     * 
     * @return int
     */
    public function count(array $filters): int
    {
        $tasksBuilder = $this->repository->createQueryBuilder('t');
        $tasksBuilder->select('COUNT(t.id)');

        foreach ($filters as $field => $condition) {

            if (!in_array($field, self::FIELDS, true)) {
                throw new \InvalidArgumentException("Недопустимый оператор 5: $field");
            }

            $operator = (is_array($condition) && isset($condition['operator'])) ? $condition['operator'] : '=';

            if (!in_array($operator, self::OPERATORS, true)) {
                throw new \InvalidArgumentException("Недопустимый оператор 6: $operator");
            }

            $value = (is_array($condition) && isset($condition['value'])) ? $condition['value'] : $condition;

            $tasksBuilder->andWhere(sprintf('t.%s %s :%s', $field, $operator, $field))
                ->setParameter($field, $value);
        }

        return (int) $tasksBuilder
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAverageCompletionTime(Project $project): float
    {
        $sql = "
            SELECT AVG(EXTRACT(EPOCH FROM (completed_at - created_at))) AS avgDuration
            FROM tasks
            WHERE project_id = :projectId
            AND completed_at IS NOT NULL
            AND status != 'canceled'
        ";  
        $stmt = $this->em->getConnection()->executeQuery($sql, [
            'projectId' => $project->getId(),
        ]);
        $result = $stmt->fetchOne();

        // может вернуть NULL, если нет завершённых задач
        return $result !== false ? (float) $result : 0;
    }
}
