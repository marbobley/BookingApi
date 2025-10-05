<?php
declare(strict_types=1);
namespace App\Domain\ProviderInterface;

use App\Domain\Model\ReservationModel;

interface ReservationProviderInterface
{
    public function save(ReservationModel $reservationModel): ReservationModel;

    /**
     * @return list<ReservationModel>
     */
    public function findAll(): array;

    public function findById(int $id): ReservationModel;
}
