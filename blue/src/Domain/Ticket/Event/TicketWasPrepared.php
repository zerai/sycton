<?php declare(strict_types=1);

namespace App\Domain\Ticket\Event;

class TicketWasPrepared
{
    public function __construct(private string $ticketId, private string $ticketType, private string $description)
    {
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getTicketType(): string
    {
        return $this->ticketType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
