<?php

namespace App\Controller;

use App\Domain\Ticket\Command\AssignTicket;
use App\Domain\Ticket\Command\ReleaseTicket;
use App\Domain\Ticket\Ticket;
use App\ReadModel\LastPreparedTicketsProjection;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketApiController extends AbstractController
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    #[Route("/tickets", methods: ["POST"])]
    public function prepare(Request $request): Response
    {
        $this->commandBus->sendWithRouting(Ticket::PREPARE_TICKET_TICKET, $request->request->all());

        return new RedirectResponse("/");
    }

    #[Route("/tickets/{ticketId}/cancel", methods: ["POST"])]
    public function cancel(Request $request): Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->sendWithRouting(Ticket::CANCEL_TICKET, ["ticketId" => $ticketId]);

        return new RedirectResponse("/tickets/" . $ticketId);
    }

    #[Route("/tickets/{ticketId}/assign", methods: ["POST"])]
    public function assign(Request $request): Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->sendWithRouting(Ticket::ASSIGN_TICKET, array_merge(["ticketId" => $ticketId], $request->request->all()));

        return new RedirectResponse("/tickets/" . $ticketId);
    }

    #[Route("/last")]
    public function lastPreparedTickets() : Response
    {
//        return $this->render("last_prepared_tickets.html.twig",[
//            "tickets" => $this->queryBus->sendWithRouting(LastPreparedTicketsProjection::GET_PREPARED_TICKETS)
//        ]);

        $tickets = $this->queryBus->sendWithRouting(LastPreparedTicketsProjection::GET_PREPARED_TICKETS);

        return new JsonResponse($tickets);
    }

}