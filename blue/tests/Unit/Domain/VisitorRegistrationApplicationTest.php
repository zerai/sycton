<?php declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\ApplicantRegistrationWasAcceptedEvent;
use App\Domain\RegisterVisitorApplicationCommand;
use App\Domain\VisitorRegistrationApplication;
use PHPUnit\Framework\TestCase;

class VisitorRegistrationApplicationTest extends TestCase
{
    public function testAttributesAfterConstruct(): void
    {
        $command =  RegisterVisitorApplicationCommand::createWith(1, 'a full name');

        $visitorApplication = VisitorRegistrationApplication::register($command);

        self::assertSame(1, $visitorApplication->id());
    }

    public function testRiseEventAfterRegister(): void
    {
        $command =  RegisterVisitorApplicationCommand::createWith(1, 'a full name');

        $visitorApplication = VisitorRegistrationApplication::register($command);

        self::assertInstanceOf(ApplicantRegistrationWasAcceptedEvent::class, $visitorApplication->getRecordedEvents()[0]);

    }


}
