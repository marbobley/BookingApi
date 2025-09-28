<?php

namespace App\Domain\RepositoryInterface;

interface ReservationRepositoryInterface
{
    public function findAll(): array;
}
