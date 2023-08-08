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

#[Projection(self::NAME, User::class)]
class UserListProjection
{
    final public const NAME = 'user_list_projection';

    final public const GET_USER_LIST = "getUserList";

    final public const GET_SECURITY_USER = "getSecurityUser";

    public function __construct(
        private readonly Connection $connection
    ) {
    }

    #[QueryHandler(self::GET_USER_LIST)]
    public function getUserList(): array
    {
        try {
            return $this->connection->executeQuery(
                <<<SQL
                    SELECT * FROM user_list_projection
                SQL
            )->fetchAllAssociative();
        } catch (TableNotFoundException) {
            return [];
        }
    }

    #[QueryHandler(self::GET_SECURITY_USER)]
    public function getSecurityUser(string $securityIdentifier): SecurityUser
    {
        $userData = $this->connection->executeQuery(
            <<<SQL
                SELECT email, password, roles FROM user_list_projection WHERE email = :email
            SQL,
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
        $rolesFromDb = $this->connection->executeQuery(
            <<<SQL
                SELECT roles FROM user_list_projection WHERE user_id = :user_id
            SQL,
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
        $rolesFromDb = $this->connection->executeQuery(
            <<<SQL
                SELECT roles FROM user_list_projection WHERE user_id = :user_id
            SQL,
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
        $this->connection->executeStatement(
            <<<SQL
                CREATE TABLE IF NOT EXISTS user_list_projection (
                    user_id VARCHAR(36) PRIMARY KEY,
                    email VARCHAR(25),
                    password VARCHAR(200),
                    roles VARCHAR(255)
                )
            SQL
        );
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                DELETE FROM user_list_projection
            SQL
        );
    }

    #[ProjectionDelete]
    public function delete(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                DROP TABLE user_list_projection
            SQL
        );
    }
}
