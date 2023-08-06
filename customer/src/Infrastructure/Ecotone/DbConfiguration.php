<?php declare(strict_types=1);

namespace App\Infrastructure\Ecotone;

use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class DbConfiguration
{
    #[ServiceContext]
    public function getDbalConfiguration(): DbalConfiguration
    {
        return DbalConfiguration::createWithDefaults()
            ->withDoctrineORMRepositories(true);
    }

    #[ServiceContext]
    public function distributedConsumer(): AmqpDistributedBusConfiguration
    {
        return AmqpDistributedBusConfiguration::createConsumer();
    }
}
