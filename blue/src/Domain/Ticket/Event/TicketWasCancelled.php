<?php declare(strict_types=1);

namespace App\Domain\Ticket\Event;

class TicketWasCancelled
{
    public function __construct(private string $ticketId)
    {
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }
}
