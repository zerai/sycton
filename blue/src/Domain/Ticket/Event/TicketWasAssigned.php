<?php declare(strict_types=1);

namespace App\Domain\Ticket\Event;

class TicketWasAssigned
{
    public function __construct(private string $ticketId, private string $assignedTo)
    {
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getAssignedTo(): string
    {
        return $this->assignedTo;
    }
}
