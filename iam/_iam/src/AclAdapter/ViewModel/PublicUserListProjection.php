<?php declare(strict_types=1);

namespace IdentityAccess\AclAdapter\ViewModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionDelete;
use Ecotone\EventSourcing\Attribute\ProjectionInitialization;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\User;

#[Projection(self::NAME, User::class)]
class PublicUserListProjection
{
    final public const NAME = 'public_user_list_projection';

    final public const GET_BASE_USER_LIST = 'getBaseUserList';

    public function __construct(
        private readonly Connection $connection
    ) {
    }

    #[QueryHandler(self::GET_BASE_USER_LIST)]
    public function getBaseUserList(): array
    {
        try {
            return $this->connection->executeQuery(
                <<<SQL
                    SELECT user_id, email FROM public_user_list_projection
                SQL
            )->fetchAllAssociative();
        } catch (TableNotFoundException) {
            return [];
        }
    }

    #[EventHandler]
    public function onUserWasRegistered(UserWasRegistered $event, array $metadata = []): void
    {
        $this->connection->insert(self::NAME, [
            'user_id' => $event->getUserId(),
            'email' => $event->getEmail(),
        ]);
    }

    #[ProjectionInitialization]
    public function initialization(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                CREATE TABLE IF NOT EXISTS public_user_list_projection (
                    user_id VARCHAR(36) PRIMARY KEY,
                    email VARCHAR(25)
                )
            SQL
        );
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                DELETE FROM public_user_list_projection
            SQL
        );
    }

    #[ProjectionDelete]
    public function delete(): void
    {
        $this->connection->executeStatement(
            <<<SQL
                DROP TABLE public_user_list_projection
            SQL
        );
    }
}
