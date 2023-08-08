<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Event;

class RoleWasAssignedToUser
{
    public function __construct(
        public readonly string $userId,
        public readonly string $role,
    ) {
    }
}
