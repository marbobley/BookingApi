<?php

declare(strict_types=1);

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

    public static function providerBadData(): array
    {
        $past = new \DateTimeImmutable('now-1 day');
        $past = $past->setTime(9, 30);

        $future = new \DateTimeImmutable('now+1 day');
        // closed period
        $future = $future->setTime(8, 30);

        return [
            ['',     $past, \InvalidArgumentException::class],
            ['SomeNom',     $past, \InvalidArgumentException::class],
            ['SomeNom',     $future, FunctionalException::class],
            ['SomeNom',     $future->setTime(8, 22), \InvalidArgumentException::class],
        ];
    }

    public static function providerGoodData(): array
    {
        $future = new \DateTimeImmutable('now+1 day');

        return [
            ['Nora', $future->setTime(9, 30)],
            ['Nora', $future->setTime(10, 00)],
            ['Nora', $future->setTime(9, 00)],
            ['Nora', $future->setTime(19, 30)],
            ['Nora', $future->setTime(19, 00)],
        ];
    }

    /**
     * @throws \Exception
     */
    public static function providerBadDataReservationOnTheSamePeriod(): array
    {
        return [
            [
                ['Nora1', (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->setTime(19, 0)],
                ['Nora2', (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->setTime(19, 0)],
            ],
            [
                ['Nora1', (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->setTime(18, 30)],
                ['Nora2', (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->setTime(18, 30)],
            ],
            [
                ['Nora1', (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->setTime(18, 0)],
                ['Nora2', (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->setTime(18, 0)],
            ],
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
    public function testReserverIsCalledWithReservationModelGoodDataThenReturnReservationModel(string $name, \DateTimeImmutable $date): void
    {
        $reservationModel = new ReservationModel($name, $date);
        $result = $this->reserverInterface->reserver($reservationModel);
        $this->assertInstanceOf(ReservationModel::class, $result);
        $this->assertSame($result->getUsername(), $name);
        $this->assertSame($result->getStartingDate(), $date);
        $this->assertNotNull($result->getId());
    }

    #[DataProvider('providerBadDataReservationOnTheSamePeriod')]
    public function testReserverIsCalledWithReservationOnTheSameTimeThenThrowFunctionalException(array $reservation1, array $reservation2): void
    {
        $this->expectException(FunctionalException::class);

        $reservationModel1 = new ReservationModel($reservation1[0], $reservation1[1]);
        $reservationModel2 = new ReservationModel($reservation2[0], $reservation2[1]);

        $result = $this->reserverInterface->reserver($reservationModel1);

        $this->assertInstanceOf(ReservationModel::class, $result);
        $this->assertSame($result->getUsername(), $reservation1[0]);
        $this->assertSame($result->getStartingDate(), $reservation1[1]);

        $this->reserverInterface->reserver($reservationModel2);
    }
}
