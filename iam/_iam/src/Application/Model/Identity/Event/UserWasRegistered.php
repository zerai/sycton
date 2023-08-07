<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Event;

class UserWasRegistered
{
    public function __construct(
        private readonly string $userId,
        private readonly string $email,
        private readonly string $hashedPassword
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
