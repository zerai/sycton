<?php declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecuredApiController extends AbstractController
{
    #[Route("/api", name: "api_index", methods: ["GET"])]
    public function __invoke(Request $request): Response
    {
        return new JsonResponse([
            "serviceName" => "Blue service",
            "route" => "secured by firewall"
        ]);
    }
}
