<?php declare(strict_types=1);

namespace IdentityAccess\Infrastructure;

use IdentityAccess\Application\Model\IdentityGenerator;
use Ramsey\Uuid\Uuid;

class UuidGenerator implements IdentityGenerator
{
    public function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
