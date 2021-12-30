<?php declare(strict_types=1);

namespace App\Domain;

class RegisterVisitorApplicationCommand
{
    private int $applicationId;

    private string $applicantFullname;

    private function __construct(int $applicationId, string $applicantFullname)
    {
        $this->applicationId = $applicationId;
        $this->applicantFullname = $applicantFullname;
    }

    public static function createWith(int $aggregateId, string $applicantFullname) : self
    {
        return new self($aggregateId, $applicantFullname);
    }

    public function getApplicationId(): int
    {
        return $this->applicationId;
    }

    public function getApplicantFullname(): string
    {
        return $this->applicantFullname;
    }
}