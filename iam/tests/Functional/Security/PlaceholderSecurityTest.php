<?php declare(strict_types=1);

namespace App\Tests\Functional\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaceholderSecurityTest extends WebTestCase
{
    public function testPlaceholderLoginPageIsAvailable(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
    }
}
