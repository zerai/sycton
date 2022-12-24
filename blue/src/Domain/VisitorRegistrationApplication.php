<?php declare(strict_types=1);

namespace App\Domain;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\WithAggregateEvents;

#[Aggregate]
class VisitorRegistrationApplication
{
    public const REGISTER_VISITOR_APPLICATION = 'visitorApplication.register';

    use WithAggregateEvents;

    private function __construct(#[AggregateIdentifier] private int $applicationId, private string $applicantFullname)
    {
        $this->recordThat(new ApplicantRegistrationWasAcceptedEvent($applicationId));
    }

    #[CommandHandler(self::REGISTER_VISITOR_APPLICATION)]
    public static function register(RegisterVisitorApplicationCommand $command): self
    {
        return new self($command->getApplicationId(), $command->getApplicantFullname());
    }

    public function id(): int
    {
        return $this->applicationId;
    }

    public function fullname(): string
    {
        return $this->applicantFullname;
    }
}
