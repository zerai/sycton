<?php declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\BaseUser;
use App\Repository\BaseUserRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Repository\BaseUserRepository
 * @group database
 */
class BaseUserRepositoryTest extends KernelTestCase
{
    private const UUID = '8563732e-5778-4bc4-a460-e7946f4b61ff';

    private const EMAIL = 'irrelevant@example.it';

    private const PASSWORD = 'irrelevant_password';

    private ?BaseUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->repository = static::getContainer()->get(BaseUserRepository::class);
    }

    public function testCanPersistABaseUser(): void
    {
        $uuid = Uuid::uuid4();
        $baseUser = new BaseUser($uuid, self::EMAIL, self::PASSWORD);

        $this->repository->add($baseUser, true);

        self::assertSame(1, $this->repository->count([
            'email' => self::EMAIL,
        ]));
    }

    public function testCanNotPersistADuplicateBaseUser(): void
    {
        $uuid = Uuid::fromString(self::UUID);
        $aUser = new BaseUser($uuid, self::EMAIL, self::PASSWORD);
        $this->repository->add($aUser, true);

        self::assertSame(1, $this->repository->count([
            'email' => self::EMAIL,
        ]));

        $this->repository->add($aUser, true);

        self::assertSame(1, $this->repository->count([
            'email' => self::EMAIL,
        ]));
    }

    public function testCanDetectADuplicateBaseUser(): void
    {
        self::markTestIncomplete('CUSTOM EXCEPTION OR OrmException');

        self::expectException(RuntimeException::class);

        $uuid = Uuid::fromString(self::UUID);
        $aUser = new BaseUser($uuid, self::EMAIL, self::PASSWORD);
        $this->repository->add($aUser, true);

        self::assertSame(1, $this->repository->count([
            'email' => self::EMAIL,
        ]));

        $this->repository->add($aUser, true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->repository = null;
    }
}
