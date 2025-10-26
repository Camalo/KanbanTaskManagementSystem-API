<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


// TODO::  requirements
#[Route('/', name: 'home', methods: ['GET'])]
class HomeController extends AbstractController
{
    public function __construct() {}

    public function __invoke()
    {
        return new JsonResponse(
            data: 'Все ОКЕЙ!',
            status: Response::HTTP_OK
        );
    }
}
