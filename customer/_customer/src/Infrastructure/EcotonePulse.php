<?php declare(strict_types=1);

namespace Infrastructure;

use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class EcotonePulse
{
    #[ServiceContext]
    public function distributedConsumer(): AmqpDistributedBusConfiguration
    {
        return AmqpDistributedBusConfiguration::createConsumer();
    }
}
