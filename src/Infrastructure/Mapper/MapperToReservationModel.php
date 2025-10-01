<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Model\ReservationModel;
use App\Infrastructure\Entity\Reservation;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class MapperToReservationModel 
{
    public function __construct(private ObjectMapperInterface $objectMapper)
    {
    }

    public function mapperModelToEntity(ReservationModel $object): Reservation
    {
        return $this->objectMapper->map($object, Reservation::class);
    }

    public function mapperEntityToModel(Reservation $object) : ReservationModel {
        return $this->objectMapper->map($object , ReservationModel::class);
    }
}
