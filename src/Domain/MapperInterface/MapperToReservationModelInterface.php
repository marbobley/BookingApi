<?php 

namespace App\Domain\MapperInterface;

use App\Domain\Model\ReservationModel;

interface MapperToReservationModelInterface{
    function mapper(ReservationModel $object) : Object;
}