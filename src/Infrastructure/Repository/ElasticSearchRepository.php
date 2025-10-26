<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Repository;

use Kamalo\KanbanTaskManagementSystem\Domain\Repository\SearchRepositoryInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;

class ElasticSearchRepository implements SearchRepositoryInterface
{
    /**
     * @var Elastic\Elasticsearch\Client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([getenv('ELASTIC_HOST')])
            ->setBasicAuthentication(getenv('ELASTIC_USER'), getenv('ELASTIC_PASSWORD'))
            ->setSSLVerification(false)
            ->build();
    }

    public function search(string $query): array
    {
        $params = [
            'index' => getenv('ELASTIC_INDEX'),
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'multi_match' => [
                                    'query' => $query,
                                    'fields' => ['title', 'description', 'owner', 'assignee', 'project'],
                                    'fuzziness' => 'AUTO'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'size'  => (int)getenv('SEARCH_SIZE')
        ];

        $response = $this->client->search($params);

        return $response->asArray();
    }

    public function save(Task $task)
    {
        $params = [
            'index' => getenv('ELASTIC_INDEX'), // например 'tasks'
            'id'    => $task->getId(),          // id документа = id задачи
            'body'  => [
                'title'       => $task->getTitle()->getValue(),
                'description' => $task->getDescription()?->getValue(),
                'status'      => $task->getStatus(),
                'priority'    => $task->getPriority(),
                'dueDate'     => $task->getDueDate()?->format('c'),
                'updatedAt'   => $task->getUpdatedAt()?->format('c'),
                'project'     => $task->getProject()->getId(),
                'assignee'    => $task->getAssignee()?->getName()->getFullName(),
                'owner'       => $task->getOwner()->getName()->getFullName(),
            ],
        ];

        try {
            $this->client->index($params);
        } catch (\Throwable $e) {
            // TODO:: Логировать, а не бросать исключение, или сразу внедрить очереди
            throw new \RuntimeException('Ошибка сохранения задачи в Elasticsearch: ' . $e->getMessage(), 0, $e);
        }
    }
}
