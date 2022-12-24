<?php declare(strict_types=1);

namespace App\Domain;

class RegisterVisitorApplicationCommand
{
    private function __construct(
        private readonly int $applicationId,
        private readonly string $applicantFullname
    ) {
    }

    public static function createWith(int $aggregateId, string $applicantFullname): self
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
