<?php

namespace App\Controller;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceImpl\ReserverImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(ReserverImpl $reserver): Response
    {
        $reservationModel = new ReservationModel(
            'nora',
            new \DateTimeImmutable('2024-06-20T10:00:00+00:00'),
            60
        );
        $reserver2 = $reserver->reserver($reservationModel);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
