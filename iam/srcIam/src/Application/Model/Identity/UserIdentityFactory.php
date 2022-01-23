<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity;

use IdentityAccess\Application\Model\IdentityGenerator;

class UserIdentityFactory
{
    public function __construct(
        private IdentityGenerator $generator
    ) {
    }

    public function nextId(): UserId
    {
        return UserId::fromString($this->generator->generateUuid());
    }
}
