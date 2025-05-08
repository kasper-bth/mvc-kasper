<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    public function testCardGraphicCreation(): void
    {
        $card = new CardGraphic('hearts', 'ace');
        $this->assertInstanceOf('\App\Card\CardGraphic', $card);
        $this->assertInstanceOf('\App\Card\Card', $card);
    }

    public function testGetAsString(): void
    {
        $card = new CardGraphic('diamonds', 'king');
        $this->assertEquals('K♦', $card->getAsString());
    }

    public function testGetColor(): void
    {
        $redCard = new CardGraphic('hearts', 'ace');
        $blackCard = new CardGraphic('spades', '2');
        
        $this->assertEquals('red', $redCard->getColor());
        $this->assertEquals('black', $blackCard->getColor());
    }

    public function testAllSymbols(): void
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
        
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $card = new CardGraphic($suit, $value);
                $this->assertNotEmpty($card->getAsString());
            }
        }
    }
}