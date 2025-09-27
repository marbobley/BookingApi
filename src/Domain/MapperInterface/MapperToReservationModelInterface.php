<?php

namespace App\Domain\MapperInterface;

use App\Domain\Model\ReservationModel;

interface MapperToReservationModelInterface
{
    public function mapper(ReservationModel $object): object;
}
