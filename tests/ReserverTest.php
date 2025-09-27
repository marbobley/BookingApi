<?php

namespace App\Tests;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Infrastructure\Repository\ReservationRepository;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
class ReserverTest extends KernelTestCase
{

    private ?ReserverInterface $reserverInterface;    

    public static function setUpBeforeClass(): void
    {
        $reservationRepository = static::getContainer()
        ->get(ReservationRepository::class);

        $reservationRepository->createQueryBuilder('r')
            ->delete()
            ->getQuery()
            ->execute();
    }
    protected function setUp(): void
    {
        $this->reserverInterface = static::getContainer()
        ->get(ReserverInterface::class);
    }


    public static function providerBadData()
    {
        return array(
          array("", new DateTimeImmutable("now"), 0), 
          array("Nora", new DateTimeImmutable("now"), 0),
          array("Nora", new DateTimeImmutable("now+1"), -10),
          array("Nora", new DateTimeImmutable("now+1"), 0),
          array("Nora", new DateTimeImmutable("now -1 hour"), 10),
          array("Nora", new DateTimeImmutable("now +1 hour"), ReserverInterface::MAX_DURATION + 1),
        );
    }

    public static function providerGoodData()
    {
        return array(
          array("Nora", new DateTimeImmutable("now+1 hour"), 10),
          array("Nora", new DateTimeImmutable("now+1 day"), 20),
          array("Nora", new DateTimeImmutable("now+1 week"), ReserverInterface::MAX_DURATION),
          array("Nora", new DateTimeImmutable("now+1 week"), ReserverInterface::MAX_DURATION-1)
        );
    }

    #[DataProvider('providerBadData')]
    public function testReserverIsCalled_withReservationModelBadData_thenThrowInvalidArgumentException($name, $date, $duration): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel($name, $date, $duration);
        $this->reserverInterface->reserver($reservationModel);
    }

    #[DataProvider('providerGoodData')]
    public function testReserverIsCalled_withReservationModelGoodData_thenReturnNull($name, $date, $duration): void
    {   
        $reservationModel = new ReservationModel($name, $date, $duration);
        $result = $this->reserverInterface->reserver($reservationModel);
        $this->assertNull($result);
    }

}
