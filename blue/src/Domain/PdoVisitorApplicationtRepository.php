<?php

namespace App\Domain;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Ecotone\Modelling\Attribute\Repository;
use Ecotone\Modelling\StandardRepository;

#[Repository]
class PdoVisitorApplicationtRepository implements StandardRepository
{
    const TABLE_NAME = "visitor_application";

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    public function canHandle(string $aggregateClassName): bool
    {
        return $aggregateClassName === VisitorRegistrationApplication::class;
    }

    public function findBy(string $aggregateClassName, array $identifiers): ?object
    {
        try {
            return $this->connection->executeQuery(<<<SQL
    SELECT * FROM visitor_application
SQL
            )->fetchAllAssociative();
        }catch (TableNotFoundException) {
            return [];
        }
    }

    public function save(array $identifiers, object $aggregate, array $metadata, ?int $versionBeforeHandling): void
    {
        /** @var VisitorRegistrationApplication $aggregate */
        $this->connection->insert(self::TABLE_NAME, [
            "application_id" => $aggregate->id(),
            "fullname" => $aggregate->fullname(),
        ]);
    }
}