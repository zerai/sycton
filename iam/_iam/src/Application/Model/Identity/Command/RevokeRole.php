<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Command;

class RevokeRole
{
    public function __construct(
        public readonly string $role,
        public readonly string $userId,
    ) {
    }
}
