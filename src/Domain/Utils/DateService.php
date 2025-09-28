<?php 

namespace App\Domain\Utils;

use DateTime;
use DateTimeImmutable;

class DateService{

    public function IsDateBetween(DateTimeImmutable $dateToCheck , DateTimeImmutable $startDate, DateTimeImmutable $endDate){

        if($dateToCheck >= $startDate && $dateToCheck < $endDate)
            return true;
        return false;
    }
}