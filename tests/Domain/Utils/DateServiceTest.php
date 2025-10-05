<?php

declare(strict_types=1);

namespace App\tests\Domain\Utils;

use App\Domain\Model\HourRange;
use App\Domain\Utils\DateService;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DateServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public static function providerData()
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

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

    /**
     * @throws Exception
     */
    public static function providerDataException(): array
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
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

    public function testDateServiceGetOpeningDateThenGetOpeningDate()
    {
        $dateService = new DateService();

        $date = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $dateOpeningExpected = $date->setTime(9, 45);
        $dateClosingExpected = $date->setTime(19, 45);

        $open = new HourRange(9, 45);
        $close = new HourRange(19, 45);

        $openingDate = $dateService->getOpening($date, $open);
        $closingDate = $dateService->getClosing($date, $close);

        $this->assertEquals($dateOpeningExpected, $openingDate, 'Opening date is not corresponding to Expected date');
        $this->assertEquals($dateClosingExpected, $closingDate, 'Closing date is not corresponding to Expected date');
    }
}
