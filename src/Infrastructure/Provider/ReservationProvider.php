<?php
declare(strict_types=1);

namespace App\Infrastructure\Provider;

use App\Domain\Model\ReservationModel;
use App\Domain\ProviderInterface\ReservationProviderInterface;
use App\Infrastructure\Entity\Reservation;
use App\Infrastructure\Mapper\MapperToReservationModel;
use App\Infrastructure\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class ReservationProvider implements ReservationProviderInterface
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
        return $this->mapperToReservationModel->mapperEntityToModel($reservationEntity);
    }

    public function findAll(): array
    {
        $func = function (Reservation $value): ReservationModel {
            $reservationModel = $this->mapperToReservationModel->mapperEntityToModel($value);
            $reservationModel->setId($value->getId());

            return $reservationModel;
        };

        return array_map($func, $this->reservationRepository->findAll());
    }

    public function findById(int $id): ReservationModel
    {
        return $this->mapperToReservationModel->mapperEntityToModel(
            $this->reservationRepository->findOneBy(['id' => $id])
        );
    }
}
