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
use IdentityAccess\Application\Model\Identity\Event\UserPasswordWasChanged;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Infrastructure\Authentication\SecurityUser;

#[Projection('UserList', User::class)]
class UserList
{
    public const GET_USER_LIST = "getUserList";

    public const GET_SECURITY_USER = "getSecurityUser";

    public function __construct(
        private Connection $connection
    ) {
    }

    #[EventHandler]
    public function addUser(UserWasRegistered $event, array $metadata): void
    {
        $this->connection->executeStatement(
            <<<SQL
                INSERT INTO users VALUES (?,?,?)
            SQL,
            [$event->getUserId(), $event->getEmail(), $event->getHashedPassword()]
        );
    }

    #[EventHandler]
    public function changePassword(UserPasswordWasChanged $event, array $metadata): void
    {
        $this->connection->update('users', [
            "password" => $event->getPassword(),
        ], [
            "user_id" => $event->getUserId(),
        ]);
    }

    #[ProjectionInitialization]
    public function initialization(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                CREATE TABLE IF NOT EXISTS users (
                    user_id VARCHAR(36) PRIMARY KEY,
                    email VARCHAR(25),
                    password VARCHAR(200)
                )
            SQL
        );
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                DELETE FROM users
            SQL
        );
    }

    #[ProjectionDelete]
    public function delete(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                DROP TABLE users
            SQL
        );
    }

    #[QueryHandler(self::GET_USER_LIST)]
    public function getUserList(): array
    {
        try {
            return $this->connection->executeQuery(
                <<<SQL
                    SELECT * FROM users
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
                SELECT email, password FROM users WHERE email = :email
            SQL,
            [
                "email" => $securityIdentifier,
            ]
        )->fetchAllAssociative()[0];

        return SecurityUser::createFromReadModel($userData['email'], $userData['password']);
    }
}
