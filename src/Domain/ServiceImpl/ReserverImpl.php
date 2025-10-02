<?php

namespace App\Domain\ServiceImpl;

use App\Domain\Model\ReservationModel;
use App\Domain\ProviderInterface\ReservationProviderInterface;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Domain\Utils\DateService;
use App\Exception\FunctionalException;

/*
    Coeur du domaine métier
    Le but est de pouvoir faire des reservation sur des transches horraires de 30 minutes, sur heure plein 08:00 / 08:30 / 09:30
*/
class ReserverImpl implements ReserverInterface
{
    public function __construct(
        private ReservationProviderInterface $reservationProvider,
        private DateService $dateService)
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

        $periodIsAlreadyInUse = false;

        if ($periodIsAlreadyInUse) {
            throw new FunctionalException('Period is already in use');
        }

        $dateOpen = $this->dateService->getOpening($reservation->getStartingDate(), 9, 30);
        $dateClosed = $this->dateService->getClosing($reservation->getStartingDate(), 19, 30);

        $reservationIsOnOpenTime = $this->dateService->IsDateBetween($reservation->getStartingDate(), $dateOpen, $dateClosed);
        if (!$reservationIsOnOpenTime) {
            throw new FunctionalException('Reservation date is not on opening time');
        }
        // Jour férié  https://calendrier.api.gouv.fr/jours-feries/
        // Week end
        // vacances

        $reservationMap = $this->reservationProvider->save($reservation);

        return $reservationMap;
    }

    public function getReservations(): array
    {
        return $this->reservationProvider->findAll();
    }
}
