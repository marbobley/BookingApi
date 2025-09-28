<?php

namespace App\Domain\ServiceImpl;

use App\Domain\MapperInterface\MapperToReservationModelInterface;
use App\Domain\Model\ReservationModel;
use App\Domain\RepositoryInterface\ReservationRepositoryInterface;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Exception\FunctionalException;
use Doctrine\ORM\EntityManagerInterface;

/*
    Coeur du domaine métier 
    Le but est de pouvoir faire des reservation sur des transches horraires de 30 minutes, sur heure plein 08:00 / 08:30 / 09:30 
    La durée est sauvegardé mais peut être basculé sur une date de fin 
*/
class ReserverImpl implements ReserverInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private MapperToReservationModelInterface $objectMapper, private ReservationRepositoryInterface $reservationRepositoryInterface)
    {
    }

    /*
        * @param ReservationModel $reservation
        * @return ReservationModel|null
    */
    public function reserver(ReservationModel $reservation): ?ReservationModel
    {
        if (empty($reservation->getUsername())) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        if ($reservation->getStartingDate() < new \DateTimeImmutable('now')) {
            throw new \InvalidArgumentException('Date cannot be in the past');
        }

        if ($reservation->getMinuteDuration() <= 0) {
            throw new \InvalidArgumentException('Duration must be positive and different from zero');
        }
        if ($reservation->getMinuteDuration() > self::MAX_DURATION) {
            throw new \InvalidArgumentException('Duration cannot exceed '.self::MAX_DURATION.' minutes');
        }

        $periodIsAlreadyInUse = false; 

        if($periodIsAlreadyInUse)
        {
            throw new FunctionalException("Period is already in use");
        }
        $reservationMap = $this->objectMapper->mapper($reservation);
        $this->entityManager->persist($reservationMap);
        $this->entityManager->flush();

        $reservation->setIsReserved(true);
        $reservation->setId($reservationMap->getId());

        return $reservation;
    }

    public function getReservations() :array{
        return $this->reservationRepositoryInterface->findAll();
    }
}
