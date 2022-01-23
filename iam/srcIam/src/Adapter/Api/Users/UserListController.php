<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api\Users;

use Ecotone\Modelling\QueryBus;
use IdentityAccess\Application\Model\Identity\ReadModel\UserList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserListController extends AbstractController
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    #[Route("/users", name: 'api_users', methods: ["GET"])]
    public function __invoke(Request $request): Response
    {
        $users = $this->queryBus->sendWithRouting(UserList::GET_USER_LIST);

        return new JsonResponse($users);
    }
}
