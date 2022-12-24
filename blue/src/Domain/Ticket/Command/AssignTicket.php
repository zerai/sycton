<?php declare(strict_types=1);

namespace App\Domain\Ticket\Command;

class AssignTicket
{
    public function __construct(
        private readonly string $ticketId,
        private readonly string $assignTo
    ) {
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
