<?php

namespace App\Tests\Card;

use App\Card\GameState;
use App\Card\CardHand;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

class GameStateTest extends TestCase
{
    public function testInitialState(): void
    {
        $state = new GameState();
        
        $this->assertInstanceOf(CardHand::class, $state->getPlayerHand());
        $this->assertInstanceOf(CardHand::class, $state->getBankHand());
        $this->assertEquals(0, $state->getPlayerScore());
        $this->assertEquals(0, $state->getBankScore());
        $this->assertFalse($state->isGameOver());
    }

    public function testScoreUpdates(): void
    {
        $state = new GameState();
        $state->setPlayerScore(15);
        $state->setBankScore(18);
        
        $this->assertEquals(15, $state->getPlayerScore());
        $this->assertEquals(18, $state->getBankScore());
    }

    public function testGameOver(): void
    {
        $state = new GameState();
        $state->endGame();
        
        $this->assertTrue($state->isGameOver());
    }
}