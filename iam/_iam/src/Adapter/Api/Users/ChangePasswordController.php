<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api\Users;

use Ecotone\Modelling\CommandBus;
use IdentityAccess\Application\Model\Identity\Command\ChangeUserPassword;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Infrastructure\Authentication\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route("/users/change-password", name: 'api_users_change_password', methods: ["POST"])]
    public function __invoke(Request $request): Response
    {
        $this->commandBus->sendWithRouting(
            User::CHANGE_PASSWORD,
            new ChangeUserPassword(
                $request->request->get('userId'),
                SecurityUser::encryptPassword($request->request->get('password'), $this->passwordHasher)
            ),
        );

        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        return $response;
    }
}
