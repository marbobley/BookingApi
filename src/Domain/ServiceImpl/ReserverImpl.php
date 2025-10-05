<?php

declare(strict_types=1);

namespace App\Domain\ServiceImpl;

use App\Domain\Model\ReservationModel;
use App\Domain\ProviderInterface\ReservationProviderInterface;
use App\Domain\ServiceInterface\ParameterInterface;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Domain\Utils\DateService;
use App\Exception\FunctionalException;

/*
    Coeur du domaine métier
    Le but est de pouvoir faire des reservation sur des transches horraires de 30 minutes, sur heure plein 08:00 / 08:30 / 09:30
*/
readonly class ReserverImpl implements ReserverInterface
{
    public function __construct(
        private ReservationProviderInterface $reservationProvider,
        private ParameterInterface $parameter,
        private DateService $dateService)
    {
    }

    /*
        * @param ReservationModel $reservation
        * @return ReservationModel|null
    */
    /**
     * @throws FunctionalException
     * @throws \InvalidArgumentException
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

        $openingRange = $this->parameter->getOpenHourRange();
        $open = $openingRange->getOpening();
        $close = $openingRange->getClosing();

        $dateOpen = $this->dateService->getOpening($reservation->getStartingDate(), $open);
        $dateClosed = $this->dateService->getClosing($reservation->getStartingDate(), $close);

        $reservationIsOnOpenTime = $this->dateService->IsDateBetween($reservation->getStartingDate(), $dateOpen, $dateClosed);
        if (!$reservationIsOnOpenTime) {
            throw new FunctionalException('Reservation date is not on opening time');
        }
        // Jour férié  https://calendrier.api.gouv.fr/jours-feries/
        // Week end
        // vacances

        return $this->reservationProvider->save($reservation);
    }

    public function getReservations(): array
    {
        return $this->reservationProvider->findAll();
    }

    public function getReservation(int $id): ReservationModel
    {
        return $this->reservationProvider->findById($id);
    }
}
