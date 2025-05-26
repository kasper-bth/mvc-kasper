<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;
use App\Proj\BankStrategy;
use App\Proj\ProjHand;
use App\Proj\ProjDeck;
use App\Proj\ScoreCalculator;

class BankStrategyTest extends TestCase
{
    public function testBankStrategyEdgeCases(): void
    {
        $strategy = new BankStrategy();
        
        $this->assertTrue($strategy->shouldDraw(16, 20));
        $this->assertFalse($strategy->shouldDraw(17, 20));
        $this->assertFalse($strategy->shouldDraw(16, 22));
        $this->assertFalse($strategy->shouldDraw(21, 20));
    }

    public function testPlayTurn(): void
    {
        $strategy = new BankStrategy();
        $hand = new ProjHand();
        $deck = new ProjDeck();
        $calculator = new ScoreCalculator();
        $playerHands = [new ProjHand()];
        
        $strategy->playTurn($hand, $deck, $calculator, $playerHands);
        
        $score = $calculator->calculate($hand);
        $this->assertTrue($score >= 17 || $score > 21);
    }
}