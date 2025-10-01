<?php

namespace App\Infrastructure\Provider;

use App\Domain\Model\ReservationModel;
use App\Domain\ProviderInterface\ReservationProviderInterface;
use App\Infrastructure\Mapper\MapperToReservationModel;
use App\Infrastructure\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationProvider implements ReservationProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository  $reservationRepository,
        private MapperToReservationModel $mapperToReservationModel
    )
    {
        
    }

    function save(ReservationModel $reservationModel) : ReservationModel {

        $reservationEntity = $this->mapperToReservationModel->mapper($reservationModel);

        $this->entityManager->persist($reservationEntity);
        $this->entityManager->flush();
        
        $reservationModel->setIsReserved(true);
        $reservationModel->setId($reservationEntity->getId());

        return $reservationModel;

    }
    function findAll() : array {
        return $this->reservationRepository->findAll();        
    }
}
