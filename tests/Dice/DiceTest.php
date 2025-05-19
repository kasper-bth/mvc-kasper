<?php

namespace App\Tests\Dice;

use App\Dice\Dice;
use PHPUnit\Framework\TestCase;

class DiceTest extends TestCase
{
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }

    public function testRollDice(): void
    {
        $die = new Dice();
        $initialValue = $die->getValue();
        
        $newValue = $die->roll();
        
        $this->assertNotEquals($initialValue, $newValue);
        $this->assertGreaterThanOrEqual(1, $newValue);
        $this->assertLessThanOrEqual(6, $newValue);
    }

    public function testRollChangesValue(): void
    {
        $die = new Dice();
        $values = [];
        
        for ($i = 0; $i < 10; $i++) {
            $values[$die->roll()] = true;
        }
        
        $this->assertGreaterThan(1, count($values), 
            'Dice should return different values on multiple rolls');
    }

    public function testGetAsStringFormat(): void
    {
        $die = new Dice();
        $string = $die->getAsString();
        
        $this->assertStringStartsWith('[', $string);
        $this->assertStringEndsWith(']', $string);
        $this->assertMatchesRegularExpression('/^\[\d\]$/', $string);
    }
}