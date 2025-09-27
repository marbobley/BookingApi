<?php

namespace App\Tests;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceImpl\ReserverImpl;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReserverTest extends KernelTestCase
{

    private ?ReserverImpl $reserverImpl;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->reserverImpl = static::getContainer()
        ->get(ReserverImpl::class);

    }

    public function testCheckClassofReserverImpl(): void
    {
        $this->assertInstanceOf(ReserverImpl::class, $this->reserverImpl);
    }

    public function testWhenReserverIsCalled_withReservationModelNoName_thenThrowInvalidArgumentException(): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel("",new DateTimeImmutable("now"),0);
        $this->reserverImpl->reserver($reservationModel);
    }

    public function testReserverIsCalled_withReservationModelStartingDateInThePast_thenThrowInvalidArgumentException(): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel("Nora",new DateTimeImmutable("2022-01-01 10:00:00"),0);
        $this->reserverImpl->reserver($reservationModel);
    }

    public function testReserverIsCalled_withReservationModelStartingDateNow_thenThrowInvalidArgumentException(): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel("Nora",new DateTimeImmutable("now"),0);
        $this->reserverImpl->reserver($reservationModel);
    }

    public function testReserverIsCalled_withReservationModelMinuteDurationNegative_thenThrowInvalidArgumentException(): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel("Nora",new DateTimeImmutable("now +1 hour"),-10);
        $this->reserverImpl->reserver($reservationModel);
    }




    
    /*public function testReserverIsCalled_withReservationModelStartingDateNowPlusOneHour_thenThrowInvalidArgumentException(): void
    {   
        $this->expectException(InvalidArgumentException::class);
        $reservationModel = new ReservationModel("Nora",new DateTimeImmutable("now +1 hour"),0);
        $result = $this->reserverImpl->reserver($reservationModel);
    }*/
    

}
