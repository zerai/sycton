<?php declare(strict_types=1);

namespace IdentityAccess\Tests\Unit\Identity;

use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\Test\FlowTestSupport;
use IdentityAccess\Application\Model\Identity\Command\PromoteToRole;
use IdentityAccess\Application\Model\Identity\Command\RegisterUser;
use IdentityAccess\Application\Model\Identity\Command\RevokeRole;
use IdentityAccess\Application\Model\Identity\Event\UserRoleWasAssigned;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \IdentityAccess\Application\Model\Identity\User
 */

class UserTest extends TestCase
{
    public function test_verify_user_properties_on_user_registration(): void
    {
        $expectedUserId = $userId = Uuid::uuid4()->toString();
        $expectedEmail = $email = 'user.' . $userId . '@example.com';
        $expectedHashedPassword = $hashedPassword = Uuid::uuid4()->toString();

        /** Retrieve user aggregate, after calling command */
        $user = $this->getTestSupport()
            ->sendCommandWithRoutingKey(User::REGISTER_USER, new RegisterUser($email, $hashedPassword, $userId))
            ->getAggregate(User::class, $userId);

        /** Verifying aggregate id property, after calling command */
        $this->assertEquals(
            $expectedUserId,
            $retrievedUserId = $user->id(),
            sprintf("ERROR: expected User::id() '%s', got: %s.", $email, $retrievedUserId)
        );

        /** Verifying aggregate email property, after calling command */
        $this->assertEquals(
            $expectedEmail,
            $retrievedEmail = $user->email(),
            sprintf("ERROR: expected User::email() '%s', got: %s.", $email, $retrievedEmail)
        );

        /** Verifying aggregate password property, after calling command */
        $this->assertEquals(
            $expectedHashedPassword,
            $retrievedPassword = $user->password(),
            sprintf("ERROR: expected User::password() '%s', got: %s.", $hashedPassword, $retrievedPassword)
        );

        /** Verifying aggregate roles property, after calling command */
        $this->assertEquals(
            ['ROLE_USER'],
            $retrievedRoles = $user->roles(),
            vsprintf("ERROR: expected User::role() 'ROLE_USER', got: %s.", $retrievedRoles)
        );

        /** Retrieve emitted events, after calling command */
        $emittedEvents = $this->getTestSupport()
            ->sendCommandWithRoutingKey(User::REGISTER_USER, new RegisterUser($email, $hashedPassword, $userId))
            ->getRecordedEvents();

        /** Verifying emitted events, after calling command */
        self::assertEquals(
            [
                new UserWasRegistered($userId, $email, $hashedPassword),
                new UserRoleWasAssigned($userId, 'ROLE_USER'),
            ],
            $emittedEvents,
            sprintf("ERROR: User::class emitted events does not match.")
        );
    }

    public function test_changePassword(): void
    {
        self::markTestIncomplete();
    }

    public function test_assign_role(): void
    {
        $userId = Uuid::uuid4()->toString();
        $email = 'user.' . $userId . '@example.com';
        $hashedPassword = Uuid::uuid4()->toString();
        $expectedRole = $role = 'ROLE_IRRELEVANT';

        /** Retrieve user aggregate, after calling command */
        $user = $this->getTestSupport()
            ->sendCommandWithRoutingKey(User::REGISTER_USER, new RegisterUser($email, $hashedPassword, $userId))
            ->sendCommandWithRoutingKey(User::ASSIGN_ROLE, new PromoteToRole($role, $userId))
            ->getAggregate(User::class, $userId);

        /** Verifying aggregate roles property, after calling command */
        $this->assertContains(
            $expectedRole,
            $user->roles()
        );

        /** Retrieve emitted events, after calling command */
        $emittedEvents = $this->getTestSupport()
            ->sendCommandWithRoutingKey(User::REGISTER_USER, new RegisterUser($email, $hashedPassword, $userId))
            ->discardRecordedMessages()
            ->sendCommandWithRoutingKey(User::ASSIGN_ROLE, new PromoteToRole($role, $userId))
            ->getRecordedEvents();

        /** Verifying emitted events, after calling command */
        self::assertEquals(
            [
                new UserRoleWasAssigned($userId, $expectedRole),
            ],
            $emittedEvents,
            sprintf("ERROR: User::class emitted events does not match.")
        );
    }

    public function test_revoke_role(): void
    {
        $userId = Uuid::uuid4()->toString();
        $email = 'user.' . $userId . '@example.com';
        $hashedPassword = Uuid::uuid4()->toString();
        $revokedRole = $role = 'ROLE_IRRELEVANT';

        /** Retrieve user aggregate, after calling command */
        $user = $this->getTestSupport()
            ->sendCommandWithRoutingKey(User::REGISTER_USER, new RegisterUser($email, $hashedPassword, $userId))
            ->sendCommandWithRoutingKey(User::ASSIGN_ROLE, new PromoteToRole($role, $userId))
            ->sendCommandWithRoutingKey(User::REVOKE_ROLE, new RevokeRole($role, $userId))
            ->getAggregate(User::class, $userId);

        /** Verifying aggregate roles property, after calling command */
        $this->assertNotContains(
            $revokedRole,
            $user->roles()
        );

        /** Retrieve emitted events, after calling command */
        $emittedEvents = $this->getTestSupport()
            ->sendCommandWithRoutingKey(User::REGISTER_USER, new RegisterUser($email, $hashedPassword, $userId))
            ->discardRecordedMessages()
            ->sendCommandWithRoutingKey(User::ASSIGN_ROLE, new PromoteToRole($role, $userId))
            ->getRecordedEvents();

        /** Verifying emitted events, after calling command */
        self::assertEquals(
            [
                new UserRoleWasAssigned($userId, $revokedRole),
            ],
            $emittedEvents,
            sprintf("ERROR: User::class emitted events does not match.")
        );
    }

    private function getTestSupport(): FlowTestSupport
    {
        return EcotoneLite::bootstrapFlowTesting([User::class]);
    }
}
