<?php

namespace App\Domain\ServiceImpl;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Infrastructure\Entity\Reservation;

class ReserverImpl implements ReserverInterface
{
    public const MAX_DURATION = 60; // in minutes

    public function __construct(private EntityManagerInterface $entityManager, private ObjectMapperInterface $objectMapper)
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

        $reservationMap = $this->objectMapper->map($reservation, Reservation::class);
        $this->entityManager->persist($reservationMap);
        $this->entityManager->flush();

        return null;
    }
}
