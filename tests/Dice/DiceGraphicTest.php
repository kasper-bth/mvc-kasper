<?php

namespace App\Tests\Dice;

use App\Dice\DiceGraphic;
use PHPUnit\Framework\TestCase;

class DiceGraphicTest extends TestCase
{
    public function testCreateDiceGraphic(): void
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf("\App\Dice\DiceGraphic", $die);
        $this->assertInstanceOf("\App\Dice\Dice", $die);
    }

    public function testGetAsString(): void
    {
        $die = new DiceGraphic();
        $result = $die->getAsString();
        
        $this->assertIsString($result);
        $this->assertContains($result, ['⚀', '⚁', '⚂', '⚃', '⚄', '⚅']);
    }

    public function testAllRepresentationsExist(): void
    {
        $die = new DiceGraphic();
        $representations = [];
        
        for ($i = 0; $i < 100; $i++) {
            $die->roll();
            $representations[$die->getAsString()] = true;
        }
        
        $this->assertCount(6, $representations);
    }
}