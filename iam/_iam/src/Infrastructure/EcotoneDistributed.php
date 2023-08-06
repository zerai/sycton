<?php declare(strict_types=1);

namespace IdentityAccess\Infrastructure;

use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class EcotoneDistributed
{
    /**
     * @return AmqpDistributedBusConfiguration[]
     *
     * @psalm-return array{0: AmqpDistributedBusConfiguration}
     */
    #[ServiceContext]
    public function configure()
    {
        return [
            AmqpDistributedBusConfiguration::createPublisher(),
        ];
    }
}
