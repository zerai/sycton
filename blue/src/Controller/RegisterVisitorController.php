<?php declare(strict_types=1);

namespace App\Controller;

use App\Domain\VisitorRegistrationApplication;
use Ecotone\Modelling\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterVisitorController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route("/register/user", name: "visitor_registration", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $this->commandBus->sendWithRouting(VisitorRegistrationApplication::REGISTER_VISITOR_APPLICATION, $request->request->all());

        return new RedirectResponse("/");
    }
}
