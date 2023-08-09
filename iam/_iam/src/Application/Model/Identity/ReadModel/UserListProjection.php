<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionDelete;
use Ecotone\EventSourcing\Attribute\ProjectionInitialization;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use IdentityAccess\Application\Model\Identity\Event\RoleWasRevoked;
use IdentityAccess\Application\Model\Identity\Event\UserPasswordWasChanged;
use IdentityAccess\Application\Model\Identity\Event\UserRoleWasAssigned;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Infrastructure\Authentication\SecurityUser;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[Projection(self::NAME, User::class)]
class UserListProjection
{
    final public const NAME = 'user_list_projection';

    final public const GET_USER_LIST = "getUserList";

    final public const GET_SECURITY_USER = "getSecurityUser";

    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    #[QueryHandler(self::GET_USER_LIST)]
    public function getUserList(): array
    {
        $tableName = self::NAME;
        try {
            return $this->connection->executeQuery(
                "SELECT * FROM $tableName"
            )->fetchAllAssociative();
        } catch (TableNotFoundException) {
            $this->logger->critical('Projection error: $tableName table not found');
            return [];
        }
    }

    #[QueryHandler(self::GET_SECURITY_USER)]
    public function getSecurityUser(string $securityIdentifier): SecurityUser
    {
        $tableName = self::NAME;
        $userData = $this->connection->executeQuery(
            "SELECT email, password, roles FROM $tableName WHERE email = :email",
            [
                "email" => $securityIdentifier,
            ]
        )->fetchAllAssociative()[0];

        return SecurityUser::createFromReadModel((string) $userData['email'], (string) $userData['password'], explode(',', (string) $userData['roles']));
    }

    #[EventHandler]
    public function onUserWasRegistered(UserWasRegistered $event, array $metadata): void
    {
        $this->connection->insert(self::NAME, [
            'user_id' => $event->getUserId(),
            'email' => $event->getEmail(),
            'password' => $event->getHashedPassword(),
        ]);
    }

    #[EventHandler]
    public function changePassword(UserPasswordWasChanged $event, array $metadata): void
    {
        $this->connection->update(self::NAME, [
            "password" => $event->getPassword(),
        ], [
            "user_id" => $event->getUserId(),
        ]);
    }

    #[EventHandler]
    public function onRoleWasAssignedToUser(UserRoleWasAssigned $event, array $metadata): void
    {
        $tableName = self::NAME;
        $rolesFromDb = $this->connection->executeQuery(
            "SELECT roles FROM $tableName WHERE user_id = :user_id",
            [
                'user_id' => $event->userId,
            ]
        )->fetchAllAssociative()[0];

        if (null === $rolesFromDb['roles']) {
            $currentRoles = [];
        } else {
            $currentRoles = explode(",", (string) $rolesFromDb['roles']);
        }
        $currentRoles[] = $event->role;

        $this->connection->update(self::NAME, [
            "roles" => implode(",", $currentRoles),
        ], [
            "user_id" => $event->userId,
        ]);
    }

    #[EventHandler]
    public function onRoleWasRevoked(RoleWasRevoked $event, array $metadata): void
    {
        $tableName = self::NAME;
        $rolesFromDb = $this->connection->executeQuery(
            "SELECT roles FROM $tableName WHERE user_id = :user_id",
            [
                'user_id' => $event->userId,
            ]
        )->fetchAllAssociative()[0];

        if (null === $rolesFromDb['roles']) {
            $currentRoles = [];
        } else {
            $currentRoles = explode(",", (string) $rolesFromDb['roles']);
        }

        if (($key = array_search($event->role, $currentRoles)) !== false) {
            unset($currentRoles[$key]);
        }

        $this->connection->update(self::NAME, [
            "roles" => implode(",", $currentRoles),
        ], [
            "user_id" => $event->userId,
        ]);
    }

    #[ProjectionInitialization]
    public function initialization(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::NAME . ' (
                    user_id VARCHAR(36) PRIMARY KEY,
                    email VARCHAR(25),
                    password VARCHAR(200),
                    roles VARCHAR(255)
                )';
        $this->connection->executeStatement($sql);
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $sql = 'DELETE FROM ' . self::NAME;
        $this->connection->executeStatement($sql);
    }

    #[ProjectionDelete]
    public function delete(): void
    {
        $sql = 'DROP TABLE ' . self::NAME;

        $this->connection->executeStatement($sql);
    }
}
