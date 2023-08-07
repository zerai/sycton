<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api\Auth;

use Ecotone\Modelling\CommandBus;
use IdentityAccess\Application\Model\Identity\Command\RegisterUser;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Infrastructure\Authentication\SecurityUser;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class NewAccountController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route("/auth/register", name: 'auth_register', methods: ["POST"])]
    public function register(Request $request): Response
    {
        $parameters = (array) json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $email = (string) $parameters['email'];
        $plaintextPassword = (string) $parameters['password'];

        $command = new RegisterUser(
            $email,
            SecurityUser::encryptPassword($plaintextPassword, $this->passwordHasher),
            Uuid::uuid4()->toString()
        );

        $this->commandBus->sendWithRouting(User::REGISTER_USER, $command);

        return new RedirectResponse("/");
    }
}
