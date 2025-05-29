<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class GameStateTest extends TestCase
{
    private GameState $state;
    private Player $player;

    protected function setUp(): void
    {
        $this->state = new GameState();
        $this->player = new Player('test');
    }

    public function testInitialState(): void
    {
        $this->assertInstanceOf(ProjHand::class, $this->state->getBankHand());
        $this->assertEquals(0, $this->state->getBankScore());
        $this->assertFalse($this->state->isGameOver());
    }

    public function testEndGame(): void
    {
        $this->state->endGame();
        $this->assertTrue($this->state->isGameOver());
    }

    public function testBankScoreAccessors(): void
    {
        $this->state->setBankScore(17);
        $this->assertEquals(17, $this->state->getBankScore());
        
        $this->state->setBankScore(21);
        $this->assertEquals(21, $this->state->getBankScore());
    }

    public function testInitialDealComplete(): void
    {
        $this->assertTrue($this->state->isInitialDeal());
        $this->state->markInitialDealComplete();
        $this->assertFalse($this->state->isInitialDeal());
    }

    public function testBustedChecks(): void
    {
        $this->assertTrue($this->state->isBusted(22));
        $this->assertFalse($this->state->isBusted(21));
    }

    public function testHandResults(): void
    {
        $results = ['player', 'bank'];
        $this->state->setHandResults($results);
        $this->assertEquals($results, $this->state->getHandResults());
    }
}