<?php

namespace App\Tests;

use App\Domain\ServiceImpl\ReserverImpl;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReserverTest extends KernelTestCase
{

    private ?ReserverImpl $reserverImpl;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->reserverImpl = static::getContainer()
        ->get(ReserverImpl::class);
    }

    public function testCheckClassofReserverImpl(): void
    {
        $this->assertInstanceOf(ReserverImpl::class, $this->reserverImpl);
    }


}
