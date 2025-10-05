<?php
declare(strict_types=1);

namespace App\Domain\Model;

class OpenHourRange
{
    public function __construct(
        private string $day,
        private HourRange $hourOpenRange,
        private HourRange $hourCloseRange,
    ){

    }

    public function getOpening() : HourRange
    {
        return $this->hourOpenRange;
    }

    public function getClosing() : HourRange
    {
        return $this->hourCloseRange;
    }
}
