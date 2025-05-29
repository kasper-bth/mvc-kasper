<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testPlayerCreation(): void
    {
        $player = new Player('test');
        $this->assertEquals('test', $player->getNickname());
        $this->assertEquals(1000, $player->getBankroll());
    }

    public function testPlaceBet(): void
    {
        $player = new Player('test');
        $this->assertTrue($player->placeBet(100));
        $this->assertEquals(900, $player->getBankroll());
        $this->assertEquals(100, $player->getCurrentBet());
        
        $this->assertFalse($player->placeBet(1000));
    }

    public function testWinLosePush(): void
    {
        $player = new Player('test');
        $player->placeBet(100);
        
        $player->win();
        $this->assertEquals(1100, $player->getBankroll());
        
        $player->placeBet(100);
        $player->lose();
        $this->assertEquals(1000, $player->getBankroll());
        
        $player->placeBet(100);
        $player->push();
        $this->assertEquals(1000, $player->getBankroll());
    }

    public function testMultipleHandBets(): void
    {
        $player = new Player('test');
        
        $this->assertTrue($player->placeBet(100, 0));
        $this->assertTrue($player->placeBet(100, 1));
        $this->assertEquals(800, $player->getBankroll());
        
        $player->win(1.0, 0);
        $this->assertEquals(1000, $player->getBankroll());
        $this->assertEquals(100, $player->getBetForHand(1));
    }

    public function testGetTotalWinsAndLosses(): void
    {
        $player = new Player('test');
        
        $player->placeBet(100);
        $player->win();
        $this->assertEquals(100, $player->getTotalWins());
        
        $player->placeBet(100);
        $player->lose();
        $this->assertEquals(100, $player->getTotalLosses());
    }

    public function testClearBets(): void
    {
        $player = new Player('test');
        $player->placeBet(100, 0);
        $player->placeBet(100, 1);
        
        $this->assertEquals(800, $player->getBankroll());
        $this->assertEquals(100, $player->getBetForHand(0));
        $this->assertEquals(100, $player->getBetForHand(1));
        
        if (method_exists($player, 'clearBets')) {
            $player->clearBets();
            $this->assertEquals(0, $player->getCurrentBet());
            $this->assertEquals(800, $player->getBankroll());
        }
    }

    public function testCanPlaceBet(): void
    {
        $player = new Player('test', 200);
        
        $this->assertTrue($player->canPlaceBet(100, 1));
        $this->assertTrue($player->canPlaceBet(50, 3));
        $this->assertFalse($player->canPlaceBet(100, 3));
    }
}