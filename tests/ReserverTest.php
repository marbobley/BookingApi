<?php

namespace App\Tests;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceImpl\ReserverImpl;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
class ReserverTest extends KernelTestCase
{

    private ?ReserverImpl $reserverImpl;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->reserverImpl = static::getContainer()
        ->get(ReserverImpl::class);

    }


    public static function providerBadData()
    {
        return array(
          array("", new DateTimeImmutable("now"), 0), 
          array("Nora", new DateTimeImmutable("now"), 0),
          array("Nora", new DateTimeImmutable("now+1"), -10),
          array("Nora", new DateTimeImmutable("now+1"), 0),
          array("Nora", new DateTimeImmutable("now -1 hour"), 10),
          array("Nora", new DateTimeImmutable("now +1 hour"), ReserverImpl::MAX_DURATION + 1),
        );
    }

    public static function providerGoodData()
    {
        return array(
          array("Nora", new DateTimeImmutable("now+1 hour"), 10),
          array("Nora", new DateTimeImmutable("now+1 day"), 20),
          array("Nora", new DateTimeImmutable("now+1 week"), ReserverImpl::MAX_DURATION)
        );
    }

    public function testCheckClassofReserverImpl(): void
    {
        $this->assertInstanceOf(ReserverImpl::class, $this->reserverImpl);
    }

    #[DataProvider('providerBadData')]
    public function testReserverIsCalled_withReservationModelBadData_thenThrowInvalidArgumentException($name, $date, $duration): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel($name, $date, $duration);
        $this->reserverImpl->reserver($reservationModel);
    }

    #[DataProvider('providerGoodData')]
    public function testReserverIsCalled_withReservationModelGoodData_thenReturnNull($name, $date, $duration): void
    {   
        $reservationModel = new ReservationModel($name, $date, $duration);
        $result = $this->reserverImpl->reserver($reservationModel);
        $this->assertNull($result);
    }
    

}
