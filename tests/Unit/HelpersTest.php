<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_euro_to_mga_conversion(): void
    {
        $this->assertEquals(5000, euroToMga(1));
        $this->assertEquals(10000, euroToMga(2));
        $this->assertEquals(0, euroToMga(0));
        $this->assertEquals(25000, euroToMga(5));
    }

    public function test_mga_to_euro_conversion(): void
    {
        $this->assertEquals(1.0, mgaToEuro(5000));
        $this->assertEquals(2.0, mgaToEuro(10000));
        $this->assertEquals(0.5, mgaToEuro(2500));
    }

    public function test_price_format(): void
    {
        $result = price(1, true);
        $this->assertStringContainsString('Ar', $result);
    }
}
