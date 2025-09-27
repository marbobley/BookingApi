<?php 

namespace App\Domain\Model;

class ReservationModel {
    public function __construct(
        private string $username,
        private \DateTimeImmutable $startingDate,
        private int $minuteDuration
    ) {}

    public function getUsername(): string {
        return $this->username;
    }

    public function getStartingDate(): \DateTimeImmutable {
        return $this->startingDate;
    }

    public function getMinuteDuration(): int {
        return $this->minuteDuration;
    }
}