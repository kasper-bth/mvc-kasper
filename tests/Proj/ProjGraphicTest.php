<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjGraphicTest extends TestCase
{
    public function testCardGraphicCreation(): void
    {
        $card = new ProjGraphic('hearts', 'ace');
        $this->assertInstanceOf('\App\Proj\ProjGraphic', $card);
        $this->assertInstanceOf('\App\Proj\Proj', $card);
    }

    public function testGetAsString(): void
    {
        $card = new ProjGraphic('diamonds', 'king');
        $this->assertEquals('K♦', $card->getAsString());
    }

    public function testGetColor(): void
    {
        $redCard = new ProjGraphic('hearts', 'ace');
        $blackCard = new ProjGraphic('spades', '2');
        
        $this->assertEquals('red', $redCard->getColor());
        $this->assertEquals('black', $blackCard->getColor());
    }

    public function testAllSymbols(): void
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
        
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $card = new ProjGraphic($suit, $value);
                $this->assertNotEmpty($card->getAsString());
            }
        }
    }
}