<?php declare(strict_types=1);

namespace IdentityAccess\Tests\Integration\ViewModel;

use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\Test\FlowTestSupport;
use Enqueue\Dbal\DbalConnectionFactory;
use IdentityAccess\AclAdapter\ViewModel\PublicUserListProjection;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Tests\EcotoneDbConnectionConf;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PublicUserListProjectionTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $this->getTestSupport()
            ->deleteEventStream(User::class)
            ->initializeProjection('public_user_list_projection')
            ->resetProjection('public_user_list_projection')
        ;
    }

    public function test_projection_query_get_base_user_list(): void
    {
        $userId = Uuid::uuid4()->toString();
        $email = 'irrelevant@example.com';
        $password = 'irrelevant';

        $expectedProjectionQueryResult = [
            [
                'user_id' => $userId,
                'email' => $email,
            ],
        ];

        self::assertEquals(
            $expectedProjectionQueryResult,
            $this->getTestSupport()
                // 2. Providing initial events to run projection on
                ->withEventsFor($userId, User::class, [
                    new UserWasRegistered($userId, $email, $password),
                ])
                // 3. Triggering projection
                ->triggerProjection('public_user_list_projection')
                // 4. Running query on projection to validate the state
                ->sendQueryWithRouting(PublicUserListProjection::GET_BASE_USER_LIST)
        );
    }

    private function getTestSupport(): FlowTestSupport
    {
        return EcotoneLite::bootstrapFlowTestingWithEventStore(
            [PublicUserListProjection::class, User::class],
            [
                DbalConnectionFactory::class => new DbalConnectionFactory(EcotoneDbConnectionConf::databaseDns()),
                new PublicUserListProjection((self::bootKernel())->getContainer()->get('doctrine.dbal.default_connection')),

            ],
            runForProductionEventStore: true
        );
    }
}
