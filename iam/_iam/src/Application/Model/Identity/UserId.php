<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity;

class UserId implements \Stringable
{
    private function __construct(
        private readonly string $identity
    ) {
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
