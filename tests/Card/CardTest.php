<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    public function testCardCreation(): void
    {
        $card = new Card('hearts', 'ace');
        $this->assertInstanceOf('\App\Card\Card', $card);
    }

    public function testGetSuit(): void
    {
        $card = new Card('diamonds', 'king');
        $this->assertEquals('diamonds', $card->getSuit());
    }

    public function testGetValue(): void
    {
        $card = new Card('spades', 'queen');
        $this->assertEquals('queen', $card->getValue());
    }

    public function testGetAsString(): void
    {
        $card = new Card('clubs', '10');
        $this->assertEquals('10 of clubs', $card->getAsString());
    }
}