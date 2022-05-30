<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\BaseUser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class BaseUserTest extends TestCase
{
    public function testAttributesAfterConstructor()
    {
        $uuid = Uuid::uuid4();
        $email = 'irrelevant@example.it';
        $password = 'irrelevant';

        $baseUser = new BaseUser($uuid, $email, $password);

        self::assertSame($email, $baseUser->getEmail());
        self::assertSame($password, $baseUser->getPassword());
        self::assertSame(['ROLE_USER'], $baseUser->getRoles());
    }
}
