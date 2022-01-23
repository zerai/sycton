<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity;

class UserId
{
    private string $identity;

    private function __construct(string $identity)
    {
        // TODO add assert validation
        $this->identity = $identity;
    }

    public static function fromString(string $identity): static
    {
        return new static($identity);
    }

    public function equals(UserId $other): bool
    {
        return $this->identity === $other->identity;
    }

    public function __toString(): string
    {
        return $this->identity;
    }
}
