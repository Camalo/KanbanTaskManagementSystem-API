<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Security\Voter;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class TaskVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const TASK_VIEW = 'TASK_VIEW';
    public const TASK_MANAGE = 'TASK_MANAGE';
    public const TASK_CREATE = 'TASK_CREATE';


    private const ATTRIBUTES = [
        self::VIEW,
        self::TASK_VIEW,
        self::TASK_CREATE,
        self::TASK_MANAGE
    ];

    protected function supports(string $attribute, $subject): bool
    {
        if (!$subject instanceof Task) {
            return false;
        }

        return in_array(
            $attribute,
            self::ATTRIBUTES,
            true
        );
    }

    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $project = $task->getProject();

        if ($attribute === self::VIEW) {
            return true;
        }

        if ($attribute === self::TASK_VIEW) {
            return $this->canView($user, $project);
        }

        if ($attribute === self::TASK_CREATE) {
            return $this->canView($user, $project);
        }


        if ($attribute === self::TASK_MANAGE) {
            return $this->canView($user, $project) && $this->canManage($user, $task);
        }

        return false;
    }

    private function canView(User $user, Project $project): bool
    {
        $isOwner = $project->getOwner()->getId() == $user->getId();
        $isMember = $project->hasMember($user);

        return $isOwner || $isMember;
    }

    private function canManage(User $user, Task $task): bool
    {
        $isTaskOwner = $task->getOwner()->getId() == $user->getId();
        $isAssignee = $task->getAssignee()?->getId() == $user->getId();

        return $isTaskOwner || $isAssignee;
    }
}
