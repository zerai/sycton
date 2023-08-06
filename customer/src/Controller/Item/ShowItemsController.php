<?php declare(strict_types=1);

namespace App\Controller\Item;

use App\Domain\Query\ItemsQuery;
use Ecotone\Modelling\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowItemsController extends AbstractController
{
    public function __construct(
        private QueryBus $queryBus,
    ) {
    }

    #[Route("/items", methods: ["GET"])]
    public function register(Request $request): Response
    {
        $query = new ItemsQuery();
        $users = $this->queryBus->send($query);

        return new JsonResponse($users);
    }
}
