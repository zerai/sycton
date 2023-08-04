<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Command;

class RegisterUser
{
    public function __construct(
        private string $email,
        private string $hashedPassword,
        private string $userId
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
