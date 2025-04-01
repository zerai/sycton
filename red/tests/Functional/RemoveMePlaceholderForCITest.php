<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RemoveMePlaceholderForCITest extends WebTestCase
{
    public function testPlaceholderImStillHere(): void
    {
        self::assertNotSame('Please remove me', 'I\'m still here');
    }
}
