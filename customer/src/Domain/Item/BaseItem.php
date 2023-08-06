<?php declare(strict_types=1);

namespace App\Domain\Item;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ramsey\Uuid\Uuid;

#[Aggregate]
#[Entity]
#[Table("base_item")]
class BaseItem
{
    #[AggregateIdentifier]
    #[Id]
    #[Column(type: "string")]
    private string $itemId;

    #[Column(type: "string")]
    private string $name;

    private function __construct(string $itemId, string $name)
    {
        $this->itemId = $itemId;
        $this->name = $name;
    }

    #[CommandHandler("registerItem")]
    public static function register(string $name): static
    {
        return new static(Uuid::uuid4()->toString(), $name);
    }
}
