<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjDeckTest extends TestCase
{
    public function testCreateDeck(): void
    {
        $deck = new ProjDeck();
        $this->assertInstanceOf('\App\Proj\ProjDeck', $deck);
        $this->assertCount(52, $deck->getCards());
    }

    public function testShuffle(): void
    {
        $deck = new ProjDeck();
        $originalOrder = $deck->getString();
        $deck->shuffle();
        $shuffledOrder = $deck->getString();
        
        $this->assertCount(52, $shuffledOrder);
        $this->assertNotEquals($originalOrder, $shuffledOrder);
    }

    public function testDrawCard(): void
    {
        $deck = new ProjDeck();
        $initialCount = $deck->getNumberCards();
        $card = $deck->drawCard();
        
        $this->assertInstanceOf('\App\Proj\Proj', $card);
        $this->assertEquals($initialCount - 1, $deck->getNumberCards());
    }

    public function testGetSortedCards(): void
    {
        $deck = new ProjDeck();
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
        $hand = new ProjHand();
        $hand->addCard(new ProjGraphic('diamonds', 'ace'));
        $hand->addCard(new ProjGraphic('spades', '2'));
        
        $strings = $hand->getString();
        $this->assertEquals(['A♦', '2♠'], $strings);
    }
}