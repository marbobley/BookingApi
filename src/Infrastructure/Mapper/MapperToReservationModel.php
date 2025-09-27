<?php

namespace App\Infrastructure\Mapper;

use App\Domain\MapperInterface\MapperToReservationModelInterface;
use App\Domain\Model\ReservationModel;
use App\Infrastructure\Entity\Reservation;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class MapperToReservationModel implements MapperToReservationModelInterface
{
    public function __construct(private ObjectMapperInterface $objectMapper)
    {
    }

    public function mapper(ReservationModel $object): Reservation
    {
        return $this->objectMapper->map($object, Reservation::class);
    }
}
