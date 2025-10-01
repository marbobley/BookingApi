<?php

namespace App\Domain\ProviderInterface;

use App\Domain\Model\ReservationModel;

interface ReservationProviderInterface
{
    function save(ReservationModel $reservationModel) : ReservationModel ;
    /**
     * @return list<ReservationModel> 
    */
    function findAll() : array ;
}
