<?php 

namespace App\Domain\Mapper;

use App\Domain\Model\ReservationModel;

interface MapperToReservationModelInterface{
    function mapper(ReservationModel $object) : Object;
}