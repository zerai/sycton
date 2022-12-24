<?php declare(strict_types=1);

namespace App\ReadModel;

use Ecotone\Dbal\DbalBackedMessageChannelBuilder;
use Ecotone\Messaging\Attribute\ServiceContext;

class ReadModelConfiguration
{
    final public const ASYNCHRONOUS_PROJECTIONS_CHANNEL = "asynchronous_projections";

    #[ServiceContext]
    public function getConfiguration()
    {
        return [
            DbalBackedMessageChannelBuilder::create(self::ASYNCHRONOUS_PROJECTIONS_CHANNEL),
        ];
    }
}
