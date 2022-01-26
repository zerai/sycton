<?php declare(strict_types=1);

namespace App\Domain\Ticket\Event;

class TicketWasCancelled
{
    private string $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }
}
