<?php 
namespace App\Domain\ServiceImpl;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReseverInterface;
use Doctrine\ORM\EntityManagerInterface;

class ReserverImpl implements ReseverInterface
{
    public const MAX_DURATION = 60; // in minutes

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /*
        * @param ReservationModel $reservation
        * @return ReservationModel|null
    */
    public function reserver(ReservationModel $reservation): ?ReservationModel
    {
        if (empty($reservation->getUsername())) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }

        if( $reservation->getStartingDate() < new \DateTimeImmutable("now")) {
            throw new \InvalidArgumentException("Date cannot be in the past");
        }

        if( $reservation->getMinuteDuration() <= 0) {
            throw new \InvalidArgumentException("Duration must be positive and different from zero");
        }
        if( $reservation->getMinuteDuration() > self::MAX_DURATION) {
            throw new \InvalidArgumentException("Duration cannot exceed ".self::MAX_DURATION." minutes");
        }

        return null;
    }
}