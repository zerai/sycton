<?php declare(strict_types=1);

namespace IdentityAccess\Tests\Integration\ReadModel;

use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\Test\FlowTestSupport;
use Enqueue\Dbal\DbalConnectionFactory;
use IdentityAccess\Application\Model\Identity\ReadModel\UserList;
use IdentityAccess\Application\Model\Identity\ReadModel\UserListProjection;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Tests\EcotoneDbConnectionConf;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserListTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $this->getTestSupport()
            //->deleteEventStream(User::class)
            //->initializeProjection('UserList')
            //->resetProjection('UserList')
        ;
    }

    private function getTestSupport(): FlowTestSupport
    {
        return EcotoneLite::bootstrapFlowTestingWithEventStore(
        // 1. Setting projection and aggregate that we want to resolve
        [UserList::class, User::class],
            [
                DbalConnectionFactory::class => new DbalConnectionFactory(EcotoneDbConnectionConf::databaseDns()),
                new UserList((self::bootKernel())->getContainer()->get('doctrine.dbal.default_connection')),

            ],
            //runForProductionEventStore: true
        );
    }
}
