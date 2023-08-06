<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Command;

class RegisterUser
{
    public function __construct(
        private readonly string $email,
        private readonly string $hashedPassword,
        private readonly string $userId
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
