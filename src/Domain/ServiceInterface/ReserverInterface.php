<?php
declare(strict_types=1);

namespace App\Domain\ServiceInterface;

use App\Domain\Model\ReservationModel;

interface ReserverInterface
{
    public function reserver(ReservationModel $reservation): ?ReservationModel;

    /**
     * @return list<ReservationModel>
     */
    public function getReservations(): array;

    public function getReservation(int $id): ReservationModel;
}
