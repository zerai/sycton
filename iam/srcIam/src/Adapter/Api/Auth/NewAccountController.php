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
    private CommandBus $commandBus;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(CommandBus $commandBus, UserPasswordHasherInterface $passwordHasher)
    {
        $this->commandBus = $commandBus;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route("/auth/register", name: 'auth_register', methods: ["POST"])]
    public function register(Request $request): Response
    {
        $plaintextPassword = $request->request->get('hashedPassword');

        $command = new RegisterUser(
            $request->request->get('email'),
            SecurityUser::encryptPassword($plaintextPassword, $this->passwordHasher),
            Uuid::uuid4()->toString()
        );

        $this->commandBus->sendWithRouting(User::REGISTER_USER, $command);

        return new RedirectResponse("/");
    }
}
