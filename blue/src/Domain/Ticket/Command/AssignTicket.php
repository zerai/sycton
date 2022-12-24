<?php declare(strict_types=1);

namespace App\Domain\Ticket\Command;

class AssignTicket
{
    public function __construct(private string $ticketId, private string $assignTo)
    {
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getAssignedTo(): string
    {
        return $this->assignTo;
    }
}
