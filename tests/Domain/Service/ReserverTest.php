<?php

namespace App\tests\Domain\Service;

use App\Domain\Model\ReservationModel;
use App\Domain\ServiceInterface\ReserverInterface;
use App\Exception\FunctionalException;
use App\Infrastructure\Entity\Reservation;
use App\Infrastructure\Repository\ReservationRepository;
use DateTimeImmutable;
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
        $past = new \DateTimeImmutable('now-1 day');
        $past = $past->setTime(9, 45);

        $future = new DateTimeImmutable('now+1 day');
        //closed period
        $future = $future->setTime(8, 45);

        return [
            ['',     $past, \InvalidArgumentException::class],
            ['SomeNom',     $past, \InvalidArgumentException::class],
            ['SomeNom',     $future, FunctionalException::class],
        ];
    }

    public static function providerGoodData()
    {
        $future = new DateTimeImmutable('now+1 day');

        return [
            ['Nora', $future->setTime(9, 30)],
            ['Nora', $future->setTime(9, 31)],
            ['Nora', $future->setTime(9, 45)],
            ['Nora', $future->setTime(19, 29)],
            ['Nora', $future->setTime(19, 30)],
        ];
    }

    public static function providerBadDataReservationOnTheSamePeriod()
    {
        return [
            [   
                ['Nora1', new \DateTimeImmutable('now+10 minute')],
                ['Nora2', new \DateTimeImmutable('now+10 minute')]
            ]
        ];
    }

    #[DataProvider('providerBadData')]
    public function testReserverIsCalledWithReservationModelBadDataThenThrowInvalidArgumentException(string $name, \DateTimeImmutable $date, string $expectedResult): void
    {
        $this->expectException($expectedResult);
        $reservationModel = new ReservationModel($name, $date);
        $this->reserverInterface->reserver($reservationModel);
    }

    #[DataProvider('providerGoodData')]
    public function testReserverIsCalledWithReservationModelGoodDataThenReturnReservationModeil(string $name, \DateTimeImmutable $date): void
    {
        $reservationModel = new ReservationModel($name, $date);
        $result = $this->reserverInterface->reserver($reservationModel);
        $this->assertInstanceOf(ReservationModel::class, $result);
        $this->assertSame($result->getUsername(), $name);
        $this->assertSame($result->getStartingDate(), $date);
    }
/*
    #[DataProvider('providerBadDataReservationOnTheSamePeriod')]
    public function testReserverIsCalledWithReservationOnTheSameTimeThenThrowFunctionalException(array $reservation1 , array $reservation2 ) : void {
        //$this->expectException(FunctionalException::class);

        $reservationModel1 = new ReservationModel($reservation1[0], $reservation1[1]);
        $reservationModel2 = new ReservationModel($reservation2[0], $reservation2[1]);

        $result = $this->reserverInterface->reserver($reservationModel1);
        $result = $this->reserverInterface->reserver($reservationModel2);
    }*/
}
