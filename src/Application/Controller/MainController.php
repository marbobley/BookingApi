<?php

namespace App\Application\Controller;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReserverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(ReserverInterface $reserver): JsonResponse
    {
        $reservationModel = new ReservationModel(
            'nora',
            new \DateTimeImmutable('2025-09-28T17:35:01+00:00'),
            30
        );
        $reserver2 = $reserver->reserver($reservationModel);

        return $this->json($reserver2);
    }

    #[Route('main/Reservations', name: 'app_main_reservation', methods: ['GET'])]
    public function getReservation(ReserverInterface $reserver): JsonResponse
    {
        $reserver = $reserver->getReservations();

        return $this->json($reserver);
    }
}
