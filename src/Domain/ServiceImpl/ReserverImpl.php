<?php 
namespace App\Domain\ServiceImpl;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReseverInterface;
use Doctrine\ORM\EntityManagerInterface;

class ReserverImpl implements ReseverInterface
{

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

        return null;
    }
}