<?php

namespace App\Infrastructure\Provider;

use App\Domain\Model\ReservationModel;
use App\Domain\ProviderInterface\ReservationProviderInterface;
use App\Infrastructure\Entity\Reservation;
use App\Infrastructure\Mapper\MapperToReservationModel;
use App\Infrastructure\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationProvider implements ReservationProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository $reservationRepository,
        private MapperToReservationModel $mapperToReservationModel,
    ) {
    }

    public function save(ReservationModel $reservationModel): ReservationModel
    {
        $reservationEntity = $this->mapperToReservationModel->mapperModelToEntity($reservationModel);

        $this->entityManager->persist($reservationEntity);
        $this->entityManager->flush();
        $reservationModelCreated = $this->mapperToReservationModel->mapperEntityToModel($reservationEntity);
        $reservationModelCreated->setIsReserved(true);

        return $reservationModelCreated;
    }

    public function mapreserver($reservation)
    {
        return $this->mapperToReservationModel->mapperEntityToModel($reservation);
    }

    public function findAll(): array
    {
        $func = function (Reservation $value): ReservationModel {
            $reserv = $this->mapperToReservationModel->mapperEntityToModel($value);
            $reserv->setId($value->getId());

            return $reserv;
        };

        return array_map($func, $this->reservationRepository->findAll());
    }
}
