<?php

namespace App\Tests\Dice;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use PHPUnit\Framework\TestCase;

class DiceHandTest extends TestCase
{
    public function testCreateDiceHand(): void
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);
        $this->assertEmpty($hand->getValues());
    }

    public function testAddAndRollDice(): void
    {
        $hand = new DiceHand();
        $hand->add(new Dice());
        $hand->add(new DiceGraphic());
        
        $this->assertEquals(2, $hand->getNumberDices());
        
        $hand->roll();
        $values = $hand->getValues();
        
        $this->assertCount(2, $values);
        $this->assertGreaterThanOrEqual(1, $values[0]);
        $this->assertLessThanOrEqual(6, $values[0]);
    }

    public function testGetStringRepresentation(): void
    {
        $hand = new DiceHand();
        $hand->add(new DiceGraphic());
        $hand->add(new DiceGraphic());
        $hand->roll();
        
        $strings = $hand->getString();
        
        $this->assertCount(2, $strings);
        foreach ($strings as $string) {
            $this->assertContains($string, ['⚀', '⚁', '⚂', '⚃', '⚄', '⚅']);
        }
    }

    public function testMultipleDiceValues(): void
    {
        $hand = new DiceHand();
        for ($i = 0; $i < 5; $i++) {
            $hand->add(new Dice());
        }
        $hand->roll();
        
        $values = $hand->getValues();
        $this->assertCount(5, $values);
        
        foreach ($values as $value) {
            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual(6, $value);
        }
    }
}