<?php declare(strict_types=1);

namespace IdentityAccess\Tests\Unit\Identity;

use Eris\Generator;
use Eris\TestTrait;
use IdentityAccess\Application\Model\Identity\UserId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;

/**
 * @covers \IdentityAccess\Application\Model\Identity\UserId
 */
class UserIdTest extends TestCase
{
    use TestTrait;

    /**
     * @eris-repeat 1000
     *
     * Property: UserId must contain the correct uuid type, uuid-4
     */
    public function testAcceptUuidType4(): void
    {
        /** @psalm-suppress DeprecatedMethod */
        $this
            ->forAll(Generator\constant(Uuid::uuid4()->toString()))
            ->then(
                /** @psalm-param string $default */
                function ($default): void {
                    $result = UserId::fromString($default);

                    self::assertInstanceOf(UserId::class, $result);
                }
            );
    }

    /**
     * @eris-repeat 10000
     *
     * Property: UserId must reject invalid uuid type
     */
    public function testRejectInvalidUuidTypes(): void
    {
        self::markTestIncomplete('TODO: add input validation to UserID::class');
        /** @psalm-suppress DeprecatedMethod */
        $this
            ->forAll(
                Generator\oneOf(
                    Generator\constant(Uuid::uuid1()->toString()),
                    Generator\constant((new UuidFactory())->uuid2(0)->toString()),
                    Generator\constant((new UuidFactory())->uuid6()->toString()),
                    Generator\constant((new UuidFactory())->uuid7()->toString()),
                )
            )
            ->then(
                function (string $default): void {
                    $result = UserId::fromString($default);

                    self::assertNotInstanceOf(
                        UserId::class,
                        $result,
                        sprintf("Error: UserId::fromString() expect only uuid type-4, got invalid uuid type: %s", $default)
                    );
                }
            );
    }
}
