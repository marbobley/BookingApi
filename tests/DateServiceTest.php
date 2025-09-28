<?php

namespace App\tests;

use App\Domain\Utils\DateService;
use PHPUnit\Framework\TestCase;

class DateServiceTest extends TestCase
{
    public function testDateServiceIsDateService(): void
    {
        $dateService = new DateService(); 

    }
}
