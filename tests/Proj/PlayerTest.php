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

    public function testUpdateBet(): void
    {
        $player = new Player('test');
        $player->placeBet(100);
        $this->assertTrue($player->updateBet(50));
        $this->assertEquals(950, $player->getBankroll());
        $this->assertEquals(50, $player->getCurrentBet());
    }
}