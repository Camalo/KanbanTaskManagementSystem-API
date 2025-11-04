<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\AddMember\AddMemberRequest;
use Kamalo\KanbanTaskManagementSystem\Application\UseCase\Project\AddMember\AddMemberUseCase;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener\FieldSets\AddMemberFieldSets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[ValidateJson(AddMemberFieldSets::REQUIRED)]
#[Route(
    '/api/projects/{projectId}/members',
    name: 'project_add_member',
    methods: ['POST'],
    requirements: ['projectId' => '\d+']
)]
class AddMemberController extends AbstractController
{
    public function __construct(private AddMemberUseCase $useCase) {}

    public function __invoke(int $projectId, Request $request): JsonResponse
    {
        $data = $request->attributes->get('json_data');

        $response = ($this->useCase)(
            new AddMemberRequest(
                projectId: $projectId,
                userId: intval($data['user'])
            )
        );

        return new JsonResponse(
            data: ['message' => 'Участник ' . $response->memberName . ' добавлен в проект с id ' . $response->projectId],
            status: Response::HTTP_CREATED
        );
    }
}
