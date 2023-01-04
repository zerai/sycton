<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaceholderHomeControllerTest extends WebTestCase
{
    public function testPlaceholdeHomeController(): void
    {
        self::markTestSkipped('remove... obsolete');
        $client = static::createClient();

        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }
}
