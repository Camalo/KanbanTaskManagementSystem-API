<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\Service\Analytics;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Repository\TaskRepositoryInterface;

class AnalyticsService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function computeProjectProgress(Project $project): ProgressAnalytictsResponse
    {
        $allTasksCount = $this->taskRepository->count([
            'project' => $project,
            'status' => [
                'operator' => '!=',
                'value' => 'canceled'
            ]
        ]);

        if ($allTasksCount === 0) {
            return new ProgressAnalytictsResponse(
                progress: 0,
                allTasksCount: 0,
                completedTasksCount: 0
            );
        }

        $completedTasksCount = $this->taskRepository->count([
            'project' => $project,
            'status' => 'done'
        ]);

        return new ProgressAnalytictsResponse(
            progress: ($completedTasksCount / $allTasksCount) * 100,
            allTasksCount: $allTasksCount,
            completedTasksCount: $completedTasksCount
        );
    }

    public function ComputeProjectPerformance(Project $project): PerfomanceAnalyticsResponse
    {

        $total = $this->taskRepository->findAverageCompletionTime($project);

        $result = $this->decomposeDuration($total);

        return new PerfomanceAnalyticsResponse(
            total: $total,
            days: $result['days'],
            hours: $result['hours'],
            minutes: $result['minutes'],
            seconds: $result['seconds']
        );
    }

    public function computeProjectOverdue(Project $project): OverdueAnalyticsResponse
    {
        $ids = $this->taskRepository->findScalar(
            [
                'id'
            ],
            [
                'project' => $project,
                'dueDate' => [
                    'operator' => '<',
                    'value' => new \DateTimeImmutable(
                        'now',
                        new \DateTimeZone('UTC')
                    )
                    ],
                    'status' => [
                    'operator' => '!=',
                    'value' => 'canceled'
                ]
            ]
        );

        return new OverdueAnalyticsResponse(
            total: count($ids),
            ids: $ids
        );
    }

    private function decomposeDuration(float $totalSeconds): array
    {
        $seconds = (int) floor($totalSeconds);

        $days = intdiv($seconds, 86400);
        $seconds %= 86400;

        $hours = intdiv($seconds, 3600);
        $seconds %= 3600;

        $minutes = intdiv($seconds, 60);
        $seconds %= 60;

        return compact('days', 'hours', 'minutes', 'seconds');
    }
}
