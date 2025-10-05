<?php

declare(strict_types=1);

namespace App\Domain\Utils;

use App\Domain\Model\HourRange;
use Exception;

class DateService
{
    /**
     * @throws \Exception
     */
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
    }

    public function getOpening(\DateTimeImmutable $date, HourRange $hourRange): \DateTimeImmutable
    {
        return $date->setTime($hourRange->getHour(), $hourRange->getMinute());
    }

    public function getClosing(\DateTimeImmutable $date, HourRange $hourRange): \DateTimeImmutable
    {
        return $date->setTime($hourRange->getHour(), $hourRange->getMinute());
    }

    /**
     * Check if $dateToCheck is between $starDate and $endDate
     * Exception If $startDate > $endDate, throw InvalidArgumentException.
     *
     * @return bool if $dateToCheck is between $starDate and $endDate false if not
     *              Ambiguous case :
     *              If $startDate == $endDate == $dateToCheck then return true
     *              If $startDate == $endDate != $dateToCheck then return false
     */
    public function IsDateBetween(\DateTimeImmutable $dateToCheck, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): bool
    {
        if ($startDate > $endDate) {
            throw new \InvalidArgumentException('startDate cannot be superior (futur) of endDate');
        }

        if ($dateToCheck >= $startDate && $dateToCheck <= $endDate) {
            return true;
        }

        return false;
    }

    /**
     * Check if the date is round ie minute equal to 0 or 30
     * @param \DateTimeImmutable $getStartingDate
     * @return bool
     */
    public function isRoundDate(\DateTimeImmutable $getStartingDate): bool
    {
        $minute = $getStartingDate->format('i');
        $minuteInt = intval($minute);

        return (bool) (30 == $minuteInt | 0 == $minuteInt);
    }
}
