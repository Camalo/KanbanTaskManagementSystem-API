<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Kamalo\KanbanTaskManagementSystem\Application\Exception\{
    AccessDeniedException,
    AssigneeNotFoundException,
    AssigneeNotInProjectException,
    CannotUpdateStatusException,
    InvalidCredentialsException,
    LoginAttemptsExceededException,
    NonUniqueUserException,
    ProjectMemberAlreadyExistsException,
    ProjectNotFoundException,
    TaskNotFoundException,
    UnknownUserException
};

class ExceptionListener
{
    // TODO:: map -> adapter pattern
    private $map = [
        AccessDeniedException::class => [
            'status' => 403,
            'type'   => 'about:blank',
        ],
        AssigneeNotFoundException::class => [
            'status' => 404,
            'type'   => 'about:blank',
        ],
        AssigneeNotInProjectException::class => [
            'status' => 400,
            'type'   => 'about:blank',
        ],
        CannotUpdateStatusException::class => [
            'status' => 400,
            'type'   => 'about:blank',
        ],
        InvalidCredentialsException::class => [
            'status' => 401,
            'type'   => 'about:blank',
        ],
        LoginAttemptsExceededException::class => [
            'status' => 429,
            'type'   => 'about:blank',
        ],
        NonUniqueUserException::class => [
            'status' => 409,
            'type'   => 'about:blank',
        ],
        ProjectMemberAlreadyExistsException::class => [
            'status' => 409,
            'type'   => 'about:blank',
        ],
        ProjectNotFoundException::class => [
            'status' => 404,
            'type'   => 'about:blank',
        ],
        TaskNotFoundException::class => [
            'status' => 404,
            'type'   => 'about:blank',
        ],
        UnknownUserException::class => [
            'status' => 404,
            'type'   => 'about:blank',
        ],
    ];

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $responseData = $this->getProblemDetails($exception);

        $event->setResponse(
            new JsonResponse($responseData, $responseData['status'])
        );
    }

    private function getProblemDetails(\Throwable $exception): array
    {
        $class = get_class($exception);

        if (isset($this->map[$class])) {
            $info = $this->map[$class];
            return [
                'type'   => $info['type'],
                'status' => $info['status'],
                'detail' => $exception->getMessage(),
            ];
        }

        if ($exception instanceof HttpExceptionInterface) {
            return [
                'type'   => 'about:blank',
                'status' => $exception->getStatusCode(),
                'detail' => $exception->getMessage(),
            ];
        }

        return [
            'type'   => 'about:blank',
            'title'  => 'Internal Server Error',
            'status' => 500,
            'detail' => $exception->getMessage(),
        ];
    }
}
