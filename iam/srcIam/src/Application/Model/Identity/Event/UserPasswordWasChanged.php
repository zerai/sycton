<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Event;

class UserPasswordWasChanged
{
    public function __construct(
        private string $userId,
        private string $password
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
