<?php declare(strict_types=1);

namespace App\Domain\Ticket\Command;

class PrepareTicket
{
    public function __construct(private string $ticketType, private string $description)
    {
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
