<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Security\Voter;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ProjectVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const PROJECT_CREATE = 'PROJECT_CREATE';
    public const PROJECT_MANAGE = 'PROJECT_MANAGE';


    private const ATTRIBUTES = [
        self::VIEW,
        self::PROJECT_CREATE,
        self::PROJECT_MANAGE
    ];

    protected function supports(string $attribute, $subject): bool
    {
        if (!$subject instanceof Project) {
            return false;
        }

        return in_array(
            $attribute,
            self::ATTRIBUTES,
            true
        );
    }

    protected function voteOnAttribute(string $attribute, $project, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($attribute === self::VIEW) {
            return true;
        }

        if ($attribute === self::PROJECT_CREATE) {

            return  in_array('ROLE_MANAGER', $user->getRoles());
        }

        if ($attribute === self::PROJECT_MANAGE) {

            return $this->canCreate($user) && $this->canManage($user, $project);
        }


        return false;
    }

    private function canCreate(User $user): bool
    {
        return in_array('ROLE_MANAGER', $user->getRoles());
    }

    private function canManage(User $user, Project $project): bool
    {
        $isOwner = $project->getOwner()->getId() == $user->getId();
        $isMember = $project->hasMember($user);

        return $isOwner || $isMember;
    }
}
