<?php

namespace App\Domain\Utils;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

class DateService
{

    public function now() : DateTimeImmutable{
        return new DateTimeImmutable('now');
    }

    public function getOpening(DateTimeImmutable $date, int $hourOpening, int $minuteOpening) : DateTimeImmutable{
        return $date->setTime($hourOpening, $minuteOpening);
    }

    public function getClosing(DateTimeImmutable $date, int $hourClosing, int $minuteClosing) : DateTimeImmutable{
        return $date->setTime($hourClosing, $minuteClosing);
    }

    /**
     * Check if $dateToCheck is  is between $starDate and $endDate
     * Exception If $startDate > $endDate, throw InvalidArgumentException.
     *
     * @return bool if $dateToCheck is between $starDate and $endDate false if not
     *              Ambiguous case :
     *              If $startDate == $endDate == $dateToCheck then return true
     *              If $startDate == $endDate != $dateToCheck then return false
     */
    public function IsDateBetween(\DateTimeImmutable $dateToCheck, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate) : bool
    {
        if ($startDate > $endDate) {
            throw new \InvalidArgumentException('startDate cannot be superior (futur) of endDate');
        }

        if ($dateToCheck >= $startDate && $dateToCheck <= $endDate) {
            return true;
        }

        return false;
    }
}
