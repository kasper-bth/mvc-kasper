<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjGameTest extends TestCase
{
    private ProjGame $game;
    private Player $player;
    private ProjDeck $deck;

    protected function setUp(): void
    {
        $this->deck = new ProjDeck();
        $this->player = new Player('test');
        $this->game = new ProjGame($this->deck, $this->player);
    }

    public function testGameInitialization(): void
    {
        $this->assertCount(1, $this->game->getPlayerHands());
        $this->assertFalse($this->game->getGameOver());
    }

    public function testPlayerDraw(): void
    {
        $this->game->playerDraw();
        $this->assertCount(1, $this->game->getCurrentHand()->getCards());
    }

    public function testPlayerDrawGameOver(): void
    {
        $this->game->playerStop();
        $initialCount = count($this->game->getCurrentHand()->getCards());
        $this->game->playerDraw();
        $this->assertCount($initialCount, $this->game->getCurrentHand()->getCards());
    }

    public function testPlayerStop(): void
    {
        $this->game->playerStop();
        $this->assertTrue($this->game->getGameOver());
    }

    public function testAddHand(): void
    {
        $this->assertTrue($this->game->addHand());
        $this->assertCount(2, $this->game->getPlayerHands());
    }

    public function testGetCurrentHandIndex(): void
    {
        $this->assertEquals(0, $this->game->getCurrentHandIndex());
        $this->game->addHand();
        $this->assertEquals(0, $this->game->getCurrentHandIndex());
    }

    public function testGetHandScore(): void
    {
        $hand = new ProjHand();
        $hand->addCard(new Proj('hearts', '10'));
        $hand->addCard(new Proj('diamonds', '5'));
        $this->assertEquals(15, $this->game->getHandScore($hand));
    }

    public function testGetDeck(): void
    {
        $this->assertInstanceOf(ProjDeck::class, $this->game->getDeck());
    }

    public function testGetBankHand(): void
    {
        $this->assertInstanceOf(ProjHand::class, $this->game->getBankHand());
    }

    public function testGetBankScore(): void
    {
        $this->assertEquals(0, $this->game->getBankScore());
    }
}