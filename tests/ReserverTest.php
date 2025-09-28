<?php

namespace App\Tests;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReserverInterface;
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
            ->setStartingDate(new \DateTimeImmutable('now+1'))
            ->setMinuteDuration(ReserverInterface::MAX_DURATION);

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
        return [
            ['', new \DateTimeImmutable('now'), 0],
            ['Nora', new \DateTimeImmutable('now'), 0],
            ['Nora', new \DateTimeImmutable('now+1'), -10],
            ['Nora', new \DateTimeImmutable('now+1'), 0],
            ['Nora', new \DateTimeImmutable('now -1 hour'), 10],
            ['Nora', new \DateTimeImmutable('now +1 hour'), ReserverInterface::MAX_DURATION + 1],
        ];
    }

    public static function providerGoodData()
    {
        return [
            ['Nora', new \DateTimeImmutable('now+1 hour'), ReserverInterface::MAX_DURATION],
            ['Nora', new \DateTimeImmutable('now+1 day'), ReserverInterface::MAX_DURATION],
            ['Nora', new \DateTimeImmutable('now+1 week'), ReserverInterface::MAX_DURATION],
            ['Nora', new \DateTimeImmutable('now+1 week'), ReserverInterface::MAX_DURATION],
        ];
    }

    public static function providerBadDataReservationOnTheSamePeriod(){
        return [
            ['Nora', new \DateTimeImmutable('now'), ReserverInterface::MAX_DURATION],
            ['Nora', new \DateTimeImmutable('now'), ReserverInterface::MAX_DURATION],
            ['Nora', new \DateTimeImmutable('now'), ReserverInterface::MAX_DURATION],
            ['Nora', new \DateTimeImmutable('now'), ReserverInterface::MAX_DURATION - 1],
        ];
    }

    #[DataProvider('providerBadData')]
    public function testReserverIsCalled_withReservationModelBadData_thenThrowInvalidArgumentException($name, $date, $duration): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $reservationModel = new ReservationModel($name, $date, $duration);
        $this->reserverInterface->reserver($reservationModel);
    }

    #[DataProvider('providerGoodData')]
    public function testReserverIsCalled_withReservationModelGoodData_thenReturnReservationModeil($name, $date, $duration): void
    {
        $reservationModel = new ReservationModel($name, $date, $duration);
        $result = $this->reserverInterface->reserver($reservationModel);
        $this->assertInstanceOf(ReservationModel::class, $result);
        $this->assertTrue($result->getIsReserved());
    }
}
