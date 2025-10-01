<?php

namespace App\Domain\ProviderInterface;

use App\Domain\Model\ReservationModel;

interface ReservationProviderInterface
{
    public function save(ReservationModel $reservationModel): ReservationModel;

    /**
     * @return list<ReservationModel>
     */
    public function findAll(): array;
}
