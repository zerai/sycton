<?php declare(strict_types=1);

namespace App\Domain\Query;

use App\Domain\Item\BaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

class ItemsQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[QueryHandler]
    public function getItems(ItemsQuery $query): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select("i")
            ->from(BaseItem::class, "i")
            ->getQuery()
            ->getArrayResult();
    }
}
