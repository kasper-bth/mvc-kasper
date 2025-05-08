<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    public function testCreateEmptyHand(): void
    {
        $hand = new CardHand();
        $this->assertInstanceOf('\App\Card\CardHand', $hand);
        $this->assertCount(0, $hand->getCards());
    }

    public function testAddCard(): void
    {
        $hand = new CardHand();
        $card = new Card('hearts', 'ace');
        $hand->addCard($card);
        
        $this->assertCount(1, $hand->getCards());
        $this->assertEquals($card, $hand->getCards()[0]);
    }

    public function testGetNumberCards(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('diamonds', 'king'));
        $hand->addCard(new Card('spades', 'queen'));
        
        $this->assertEquals(2, $hand->getNumberCards());
    }

    public function testGetValues(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('clubs', '10'));
        $hand->addCard(new Card('hearts', 'jack'));
        
        $values = $hand->getValues();
        $this->assertEquals(['10', 'jack'], $values);
    }

    public function testGetString(): void
    {
        $hand = new CardHand();
        $hand->addCard(new Card('diamonds', 'ace'));
        $hand->addCard(new Card('spades', '2'));
        
        $strings = $hand->getString();
        $this->assertEquals(['ace of diamonds', '2 of spades'], $strings);
    }
}