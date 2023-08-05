<?php declare(strict_types=1);

namespace IdentityAccess\Tests\Integration\ReadModel;

use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\Test\FlowTestSupport;
use Enqueue\Dbal\DbalConnectionFactory;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\ReadModel\UserList;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Infrastructure\Authentication\SecurityUser;
use IdentityAccess\Tests\EcotoneDbConnectionConf;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserListTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $this->getTestSupport()
            ->deleteEventStream(User::class)
            ->initializeProjection('UserList')
            ->resetProjection('UserList')
        ;
    }

    public function test_projection_query_get_security_user(): void
    {
        $userId = Uuid::uuid4()->toString();
        $email = 'irrelevant@example.com';
        $password = 'irrelevant';

        $expectedProjectionQueryResult = SecurityUser::createFromReadModel($email, $password);

        self::assertEquals(
            $expectedProjectionQueryResult,
            $this->getTestSupport()
                // 2. Providing initial events to run projection on
                ->withEventsFor($userId, User::class, [
                    new UserWasRegistered($userId, $email, $password),
                ])
                // 3. Triggering projection
                ->triggerProjection('UserList')
                // 4. Running query on projection to validate the state
                ->sendQueryWithRouting(UserList::GET_SECURITY_USER, $email)
        );
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
            runForProductionEventStore: true
        );
    }
}
