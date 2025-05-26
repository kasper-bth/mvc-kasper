<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ScoreCalculatorTest extends TestCase
{
    public function testCalculateScore(): void
    {
        $calculator = new ScoreCalculator();
        $hand = new ProjHand();
        
        $hand->addCard(new Proj('hearts', '10'));
        $hand->addCard(new Proj('diamonds', '7'));
        $this->assertEquals(17, $calculator->calculate($hand));
        
        $hand = new ProjHand();
        $hand->addCard(new Proj('spades', 'king'));
        $hand->addCard(new Proj('clubs', 'queen'));
        $this->assertEquals(20, $calculator->calculate($hand));
        
        $hand = new ProjHand();
        $hand->addCard(new Proj('hearts', 'ace'));
        $hand->addCard(new Proj('diamonds', 'ace'));
        $this->assertEquals(12, $calculator->calculate($hand));
    }
}