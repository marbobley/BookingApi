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

    public function reserver(ReservationModel $reservation): ?ReservationModel
    {
        return null;
    }
}