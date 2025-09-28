<?php

namespace App\tests\Domain\Service;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Exception\FunctionalException;
use App\Infrastructure\Entity\Reservation;
use App\Infrastructure\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReserverTest extends KernelTestCase
{
    private ?ReserverInterface $reserverInterface;

    public static function setUpBeforeClass(): void
    {
        $entityManager = static::getContainer()
            ->get(EntityManagerInterface::class);

        $reservationRepository = static::getContainer()
            ->get(ReservationRepository::class);

        $reservationRepository->createQueryBuilder('r')
            ->delete()
            ->getQuery()
            ->execute();

        $reservationEntity = new Reservation();
        $reservationEntity
            ->setUsername('Bob')
            ->setStartingDate(new \DateTimeImmutable('now+1'));

        $entityManager->persist($reservationEntity);
        $entityManager->flush();        
    }

    protected function setUp(): void
    {
        $this->reserverInterface = static::getContainer()
        ->get(ReserverInterface::class);
    }

    public static function providerBadData()
    {
        $now = new \DateTimeImmutable('now');
        $now = $now->setTime(11,0);    

        return [
            ['',     $now, \InvalidArgumentException::class],
            ['Nora', $now, \InvalidArgumentException::class],
            ['Nora', $now, \InvalidArgumentException::class],
            ['Nora', $now, \InvalidArgumentException::class],
            ['Nora', $now, \InvalidArgumentException::class],
            ['Nora', $now, \InvalidArgumentException::class],
            ['Nora', $now->setTime(19,31) , FunctionalException::class],
        ];
    }

    public static function providerGoodData()
    {
        return [
            ['Nora', new \DateTimeImmutable('now+1 hour')],
            ['Nora', new \DateTimeImmutable('now+1 day')],
            ['Nora', new \DateTimeImmutable('now+1 week')],
            ['Nora', new \DateTimeImmutable('now+1 week')],
        ];
    }

    public static function providerBadDataReservationOnTheSamePeriod(){
        return [
            ['Nora', new \DateTimeImmutable('now')],
            ['Nora', new \DateTimeImmutable('now')],
            ['Nora', new \DateTimeImmutable('now')],
            ['Nora', new \DateTimeImmutable('now')],
        ];
    }

    #[DataProvider('providerBadData')]
    public function testReserverIsCalled_withReservationModelBadData_thenThrowInvalidArgumentException($name, $date, $expectedResult): void
    {
        $this->expectException($expectedResult);
        $reservationModel = new ReservationModel($name, $date);
        $this->reserverInterface->reserver($reservationModel);
    }

    #[DataProvider('providerGoodData')]
    public function testReserverIsCalled_withReservationModelGoodData_thenReturnReservationModeil($name, $date): void
    {
        $reservationModel = new ReservationModel($name, $date);
        $result = $this->reserverInterface->reserver($reservationModel);
        $this->assertInstanceOf(ReservationModel::class, $result);
        $this->assertTrue($result->getIsReserved());
    }
}
