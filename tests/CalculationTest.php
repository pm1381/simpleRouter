<?php

use Bramus\Model\Calculate;
use PHPUnit\Framework\TestCase;

class CalculationTest extends TestCase
{
    private $calc;

    protected function setUp(): void {
        $this->calc = new Calculate();
    }

    public function testCalculation()
    {
        // $actual_link = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;
        $mock = $this->createMock(Calculate::class);

        $mock
        ->method('meterCalculation')
        ->willReturnCallback(function($amount, $to, $from){
            $this->assertIsNumeric($amount);
            $this->assertIsFloat($to);
            $this->assertIsFloat($from);
            $data =  $this->calc->meterCalculation(2, 1000, 0.001);
            return $data['answer'];
        });

        $this->assertSame($mock->meterCalculation(2, 1000.0, 0.001), 2000000.0);
        //$this->assertSame($mock->meterCalculation(2, 1000.0, 1.0), 2000.0); a false test

        // $data = $this->calc->meterCalculation(12, 1000, 1);
        // $this->assertSame($data['answer'], 12000.0); // another true test without mock
    }
} 