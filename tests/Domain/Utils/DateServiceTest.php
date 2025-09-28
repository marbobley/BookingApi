<?php

namespace App\tests\Domain\Utils;

use App\Domain\Utils\DateService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class DateServiceTest extends TestCase
{
    public static function providerData()
    {
        $now = new \DateTimeImmutable('now');

        return [
            [$now->setTime(11,0), $now->setTime(11,0),$now->setTime(11,0), true ],
            [$now->setTime(10,0), $now->setTime(11,0),$now->setTime(11,0), false],
            [$now->setTime(12,0), $now->setTime(11,0),$now->setTime(11,0), false],
            [$now->setTime(10,0), $now->setTime( 9,0),$now->setTime(11,0), true ],
            [$now->setTime(12,0), $now->setTime( 9,0),$now->setTime(11,0), false],
            [$now->setTime( 9,0), $now->setTime( 9,0),$now->setTime(11,0), true ],
            [$now->setTime(11,0), $now->setTime( 9,0),$now->setTime(11,0), true ],
        ];
    }

    public static function providerDataException(){
        $now = new \DateTimeImmutable('now');
        $now = $now->setTime(11,0);

        return [
            [$now->setTime(10,0), $now->setTime(12,0,0),$now, InvalidArgumentException::class]
        ];

    }

    #[DataProvider('providerData')]
    public function testDateServiceIsDateService(
        \DateTimeImmutable $reservationDate, 
        \DateTimeImmutable $openDate , 
        \DateTimeImmutable $closeDate , 
        bool $expectResult): void
    {
        $dateService = new DateService(); 
        $this->assertEquals($expectResult, $dateService->IsDateBetween($reservationDate, $openDate, $closeDate));
    }

    #[DataProvider('providerDataException')]
    public function testDateServiceIsDateBewteen_withExceptionData_thenThrowException(
        \DateTimeImmutable $reservationDate, 
        \DateTimeImmutable $openDate , 
        \DateTimeImmutable $closeDate , 
        string $expectResult){

        $this->expectException($expectResult);

        $dateService = new DateService(); 
        $dateService->IsDateBetween($reservationDate, $openDate, $closeDate);
    }
}
