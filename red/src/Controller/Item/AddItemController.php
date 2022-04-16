<?php declare(strict_types=1);

namespace App\Controller\Item;

use Ecotone\Modelling\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddItemController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    #[Route("/items", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $name = $request->get("name");

        $this->commandBus->sendWithRouting("registerItem", $name);

        return new RedirectResponse("/");
    }
}
