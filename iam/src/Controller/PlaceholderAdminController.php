<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceholderAdminController extends AbstractController
{
    #[Route('/admin', name: 'placeholder_admin')]
    public function __invoke(): Response
    {
        return new JsonResponse([
            'applicationName' => 'Symfony es cqrs boilerplate',
        ]);
    }
}
