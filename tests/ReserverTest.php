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
          array("Nora", new DateTimeImmutable("now -1 hour"), 10)
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
    

}
