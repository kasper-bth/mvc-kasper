<?php

namespace App\Tests\Card;

use App\Card\ScoreCalculator;
use App\Card\CardHand;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

class ScoreCalculatorTest extends TestCase
{
    public function testCalculateScore(): void
    {
        $calculator = new ScoreCalculator();
        $hand = new CardHand();
        
        $hand->addCard(new Card('hearts', '7'));
        $hand->addCard(new Card('diamonds', '5'));
        $this->assertEquals(12, $calculator->calculate($hand));
        
        $hand = new CardHand();
        $hand->addCard(new Card('clubs', 'jack'));
        $hand->addCard(new Card('spades', 'queen'));
        $this->assertEquals(23, $calculator->calculate($hand));
        
        $hand = new CardHand();
        $hand->addCard(new Card('hearts', 'ace'));
        $hand->addCard(new Card('diamonds', '7'));
        $this->assertEquals(8, $calculator->calculate($hand));
        
        $hand = new CardHand();
        $hand->addCard(new Card('hearts', 'ace'));
        $hand->addCard(new Card('diamonds', 'ace'));
        $hand->addCard(new Card('clubs', 'ace'));
        $this->assertEquals(16, $calculator->calculate($hand));
    }
}