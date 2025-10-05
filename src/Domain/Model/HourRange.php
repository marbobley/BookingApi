<?php

declare(strict_types=1);

namespace App\Domain\Model;

class HourRange
{
    public function __construct(
        private int $hour,
        private int $minute,
    ) {
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }
}
