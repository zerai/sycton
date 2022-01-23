<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Event;

class UserWasRegistered
{
    public function __construct(
        private string $userId,
        private string $email,
        private string $hashedPassword
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
