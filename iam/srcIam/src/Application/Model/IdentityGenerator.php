<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model;

interface IdentityGenerator
{
    public function generateUuid();
}
