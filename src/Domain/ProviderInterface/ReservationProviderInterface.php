<?php

namespace App\Domain\ProviderInterface;

use App\Domain\Model\ReservationModel;

interface ReservationProviderInterface
{
    function save(ReservationModel $reservationModel) : ReservationModel ;
    function findAll() : array ;
}
