<?php

namespace App\tests\Domain\Utils;

use App\Domain\Utils\DateService;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DateServiceTest extends TestCase
{
    public static function providerData()
    {
        $now = new \DateTimeImmutable('now');

        return [
            [$now->setTime(11, 0), $now->setTime(11, 0), $now->setTime(11, 0), true],
            [$now->setTime(10, 0), $now->setTime(11, 0), $now->setTime(11, 0), false],
            [$now->setTime(12, 0), $now->setTime(11, 0), $now->setTime(11, 0), false],
            [$now->setTime(10, 0), $now->setTime(9, 0), $now->setTime(11, 0), true],
            [$now->setTime(12, 0), $now->setTime(9, 0), $now->setTime(11, 0), false],
            [$now->setTime(9, 0), $now->setTime(9, 0), $now->setTime(11, 0), true],
            [$now->setTime(11, 0), $now->setTime(9, 0), $now->setTime(11, 0), true],
        ];
    }

    public static function providerDataException()
    {
        $now = new \DateTimeImmutable('now');
        $now = $now->setTime(11, 0);

        return [
            [$now->setTime(10, 0), $now->setTime(12, 0, 0), $now, \InvalidArgumentException::class],
        ];
    }

    #[DataProvider('providerData')]
    public function testDateServiceIsDateService(
        \DateTimeImmutable $reservationDate,
        \DateTimeImmutable $openDate,
        \DateTimeImmutable $closeDate,
        bool $expectResult): void
    {
        $dateService = new DateService();
        $this->assertEquals($expectResult, $dateService->IsDateBetween($reservationDate, $openDate, $closeDate), 'Reservation date is not between open and close');
    }

    #[DataProvider('providerDataException')]
    public function testDateServiceIsDateBewteenWithExceptionDataThenThrowException(
        \DateTimeImmutable $reservationDate,
        \DateTimeImmutable $openDate,
        \DateTimeImmutable $closeDate,
        string $expectResult)
    {
        $this->expectException($expectResult);

        $dateService = new DateService();
        $dateService->IsDateBetween($reservationDate, $openDate, $closeDate);
    }

    public function testDateServiceGetOpeningDateThenGetOpeningDate(){
        $dateService = new DateService();

        $date = new DateTimeImmutable('now');
        $dateOpeningExpected = $date->setTime(9,45);
        $dateClosingExpected = $date->setTime(19,45);

        $openingDate = $dateService->getOpening($date, 9 , 45);
        $closingDate = $dateService->getClosing($date, 19 , 45);

        $this->assertEquals($dateOpeningExpected, $openingDate, 'Opening date is not corresponding to Expected date');
        $this->assertEquals($dateClosingExpected, $closingDate, 'Closing date is not corresponding to Expected date');
    }
}
