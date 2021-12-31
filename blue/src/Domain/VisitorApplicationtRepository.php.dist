<?php declare(strict_types=1);

namespace App\Domain;

use Ecotone\Modelling\Attribute\Repository;
use Ecotone\Modelling\StandardRepository;

#[Repository]
class VisitorApplicationtRepository implements StandardRepository
{
    private array $applications = [];

    public function canHandle(string $aggregateClassName): bool
    {
        return $aggregateClassName === VisitorRegistrationApplication::class;
    }

    public function findBy(string $aggregateClassName, array $identifiers): ?object
    {
        if (!array_key_exists($identifiers["applicationId"], $this->applications)) {
            return null;
        }

        return $this->applications[$identifiers["applicationId"]];

    }

    public function save(array $identifiers, object $aggregate, array $metadata, ?int $versionBeforeHandling): void
    {
        $this->applications[$identifiers["applicationId"]] = $aggregate;
    }
}