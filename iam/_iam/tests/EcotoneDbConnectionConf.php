<?php declare(strict_types=1);

namespace IdentityAccess\Tests;

class EcotoneDbConnectionConf
{
    public static function databaseDns(): string
    {
        return $_ENV['DATABASE_DSN'];
    }
}
