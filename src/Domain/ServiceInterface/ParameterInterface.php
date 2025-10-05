<?php
declare(strict_types=1);

namespace App\Domain\ServiceInterface;

use App\Domain\Model\OpenHourRange;

interface ParameterInterface
{
    public function getOpenHourRange(): OpenHourRange;
}
