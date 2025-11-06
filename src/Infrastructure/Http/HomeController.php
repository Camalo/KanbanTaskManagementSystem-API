<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/',
    name: 'home',
    methods: ['GET'],
)]
class HomeController extends AbstractController
{
    public function __construct() {}

    public function __invoke(): Response
    {
        return $this->render('home.html.twig');
    }
}
