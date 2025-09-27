<?php

namespace App\Domain\Model;

class ReservationModel
{
    public function __construct(
        private string $username,
        private \DateTimeImmutable $startingDate,
        private int $minuteDuration,
    ) {
    }

    private bool $isReserved = false;

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

    /**
     * Get the value of isReserved
     */ 
    public function getIsReserved()
    {
        return $this->isReserved;
    }

    /**
     * Set the value of isReserved
     *
     * @return  self
     */ 
    public function setIsReserved($isReserved)
    {
        $this->isReserved = $isReserved;

        return $this;
    }
}
