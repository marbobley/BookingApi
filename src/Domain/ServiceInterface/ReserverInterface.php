<?php

namespace App\Domain\ServiceInterface;

use App\Domain\Model\ReservationModel;

interface ReserverInterface
{
    public function reserver(ReservationModel $reservation): ?ReservationModel;
}
