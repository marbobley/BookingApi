<?php

namespace App\Domain\ServiceInterface;

use App\Domain\Model\ReservationModel;

interface ReseverInterface
{
    public function reserver(ReservationModel $reservation): ?ReservationModel;
}