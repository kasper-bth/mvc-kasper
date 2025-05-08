<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */

class DeckOfCardsTest extends TestCase
{
    public function testCreateDeck(): void
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf('\App\Card\DeckOfCards', $deck);
        $this->assertCount(52, $deck->getCards());
    }

    public function testShuffle(): void
    {
        $deck = new DeckOfCards();
        $originalOrder = $deck->getString();
        $deck->shuffle();
        $shuffledOrder = $deck->getString();
        
        $this->assertCount(52, $shuffledOrder);
        $this->assertNotEquals($originalOrder, $shuffledOrder);
    }

    public function testDrawCard(): void
    {
        $deck = new DeckOfCards();
        $initialCount = $deck->getNumberCards();
        $card = $deck->drawCard();
        
        $this->assertInstanceOf('\App\Card\Card', $card);
        $this->assertEquals($initialCount - 1, $deck->getNumberCards());
    }

    public function testGetSortedCards(): void
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $sortedCards = $deck->getSortedCards();
        
        $this->assertCount(52, $sortedCards);
        
        $this->assertEquals('clubs', $sortedCards[0]->getSuit());
        $this->assertEquals('ace', $sortedCards[0]->getValue());
        
        $this->assertEquals('spades', $sortedCards[51]->getSuit());
        $this->assertEquals('king', $sortedCards[51]->getValue());
    }

    public function testGetString(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('diamonds', 'ace'));
        $hand->addCard(new CardGraphic('spades', '2'));
        
        $strings = $hand->getString();
        $this->assertEquals(['A♦', '2♠'], $strings);
    }
}