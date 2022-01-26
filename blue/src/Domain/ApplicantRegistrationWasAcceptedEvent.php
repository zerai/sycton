<?php declare(strict_types=1);

namespace App\Domain;

class ApplicantRegistrationWasAcceptedEvent
{
    private int $applicationId;

    public function __construct(int $applicationId)
    {
        $this->applicationId = $applicationId;
    }

    public function getApplicationId(): int
    {
        return $this->applicationId;
    }
}
