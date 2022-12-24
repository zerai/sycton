<?php declare(strict_types=1);

namespace App\Domain;

class ApplicantRegistrationWasAcceptedEvent
{
    public function __construct(private int $applicationId)
    {
    }

    public function getApplicationId(): int
    {
        return $this->applicationId;
    }
}
