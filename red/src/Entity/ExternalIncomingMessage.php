<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExternalIncomingMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExternalIncomingMessageRepository::class)]
class ExternalIncomingMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $messageContent = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $recievedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageContent(): ?string
    {
        return $this->messageContent;
    }

    public function setMessageContent(string $messageContent): self
    {
        $this->messageContent = $messageContent;

        return $this;
    }

    public function getRecievedAt(): ?\DateTimeImmutable
    {
        return $this->recievedAt;
    }

    public function setRecievedAt(\DateTimeImmutable $recievedAt): self
    {
        $this->recievedAt = $recievedAt;

        return $this;
    }
}
