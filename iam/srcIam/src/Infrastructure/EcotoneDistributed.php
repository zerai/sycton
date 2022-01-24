<?php declare(strict_types=1);

namespace IdentityAccess\Infrastructure;

use Ecotone\Amqp\AmqpBackedMessageChannelBuilder;
use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class EcotoneDistributed
{
    #[ServiceContext]
    public function iamChannel()
    {
        return AmqpBackedMessageChannelBuilder::create("iam");
    }

    #[ServiceContext]
    public function configure()
    {
        return [
            AmqpDistributedBusConfiguration::createPublisher()
        ];
    }
}
