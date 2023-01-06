<?php declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceholderApiIndexController extends AbstractController
{
    #[Route('/api/index', name: 'placeholder_api_index')]
    public function __invoke(): Response
    {
        return new JsonResponse([
            'apiResource' => 'index',
        ]);
    }
}
