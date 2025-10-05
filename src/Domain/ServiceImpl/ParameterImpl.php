<?php

declare(strict_types=1);

namespace App\Domain\ServiceImpl;

use App\Domain\Model\HourRange;
use App\Domain\Model\OpenHourRange;
use App\Domain\ServiceInterface\ParameterInterface;

class ParameterImpl implements ParameterInterface
{
    public function __construct()
    {
    }

    public function getOpenHourRange(): OpenHourRange
    {
         $opening = new HourRange(9, 0);
         $closing = new HourRange(19, 30);

        return new OpenHourRange('today', $opening, $closing);
    }
}
