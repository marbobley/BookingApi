<?php

namespace App\Domain\Model;

class ReservationModel
{
    private int $id = 0;
    private bool $isReserved = false;

    public function __construct(
        private string $username,
        private \DateTimeImmutable $startingDate,
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

    /**
     * Get the value of isReserved.
     */
    public function getIsReserved() : bool
    {
        return $this->isReserved;
    }

    /**
     * Set the value of isReserved.
     *
     * @return self
     */
    public function setIsReserved(bool $isReserved)
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    /**
     * Get the value of id.
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
}
