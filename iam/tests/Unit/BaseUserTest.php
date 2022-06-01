<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\BaseUser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Entity\BaseUser
 */
class BaseUserTest extends TestCase
{
    public function testAttributesAfterConstructor(): void
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
