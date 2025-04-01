<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthCheckControllerTest extends WebTestCase
{
    public function test_health_check_route(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/health_check');

        $this->assertResponseIsSuccessful();
    }
}
