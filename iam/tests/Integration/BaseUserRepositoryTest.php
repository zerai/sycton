<?php declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\BaseUser;
use App\Repository\BaseUserRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseUserRepositoryTest extends KernelTestCase
{
    public function testCanPersistABaseUser(): void
    {
        self::bootKernel();

        /** @var BaseUserRepository $repo */
        $repo = static::getContainer()->get(BaseUserRepository::class);

        $uuid = Uuid::uuid4();
        $email = 'irrelevant@example.it';
        $password = 'irrelevant';

        $baseUser = new BaseUser($uuid, $email, $password);

        $repo->add($baseUser, true);

        self::assertSame(1, $repo->count([
            'email' => 'irrelevant@example.it',
        ]));
    }
}
