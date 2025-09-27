<?php

namespace App\Domain\ServiceInterface;

use App\Domain\Model\ReservationModel;

interface ReserverInterface
{
    public const MAX_DURATION = 60; // in minutes
    public function reserver(ReservationModel $reservation): ?ReservationModel;
}
