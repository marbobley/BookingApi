<?php

namespace App\Domain\Model;

use App\Entity\Reservation;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: Reservation::class)]
class ReservationModel
{
    public function __construct(
        #[Map(target: 'username')]
        private string $username,
        #[Map(target: 'startingDate')]
        private \DateTimeImmutable $startingDate,
        #[Map(target: 'minuteDuration')]
        private int $minuteDuration,
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getStartingDate(): \DateTimeImmutable
    {
        return $this->startingDate;
    }

    public function getMinuteDuration(): int
    {
        return $this->minuteDuration;
    }
}
