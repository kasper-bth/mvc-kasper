<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;
use App\Proj\GameState;
use App\Proj\Player;
use App\Proj\ProjHand;
use App\Proj\Proj;
use App\Proj\ScoreCalculator;

class GameStateTest extends TestCase
{
    public function testInitialState(): void
    {
        $state = new GameState();
        $this->assertInstanceOf(ProjHand::class, $state->getBankHand());
        $this->assertFalse($state->isGameOver());
    }

    public function testEndGame(): void
    {
        $state = new GameState();
        $state->endGame();
        $this->assertTrue($state->isGameOver());
    }

    public function testHasBlackjack(): void
    {
        $state = new GameState();
        $hand = new ProjHand();
        $hand->addCard(new Proj('hearts', 'ace'));
        $hand->addCard(new Proj('diamonds', 'king'));
        
        $this->assertTrue($state->hasBlackjack($hand));
        
        $hand->addCard(new Proj('spades', '2'));
        $this->assertFalse($state->hasBlackjack($hand));
    }

    public function testEvaluateResults(): void
    {
        $state = new GameState();
        $player = new Player('test');
        $playerHand = new ProjHand();
        $bankHand = new ProjHand();
        $calculator = new ScoreCalculator();
        
        $playerHand->addCard(new Proj('hearts', '10'));
        $playerHand->addCard(new Proj('diamonds', '10'));
        $playerHand->addCard(new Proj('spades', '2'));
        $player->placeBet(10);
        $state->evaluateResults($player, [$playerHand], $bankHand, $calculator);
        $this->assertEquals(990, $player->getBankroll());
        
        $player = new Player('test');
        $playerHand = new ProjHand();
        $bankHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', '10'));
        $bankHand->addCard(new Proj('hearts', '10'));
        $bankHand->addCard(new Proj('diamonds', '10'));
        $bankHand->addCard(new Proj('spades', '2'));
        $player->placeBet(10);
        $state->evaluateResults($player, [$playerHand], $bankHand, $calculator);
        $this->assertEquals(1010, $player->getBankroll());
        
        $player = new Player('test');
        $playerHand = new ProjHand();
        $bankHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', 'ace'));
        $playerHand->addCard(new Proj('diamonds', 'king'));
        $bankHand->addCard(new Proj('hearts', '10'));
        $bankHand->addCard(new Proj('diamonds', '7'));
        $player->placeBet(10);
        $state->evaluateResults($player, [$playerHand], $bankHand, $calculator);
        $this->assertEquals(1015, $player->getBankroll());
    }

    public function testGetBankScore(): void
    {
        $state = new GameState();
        $hand = new ProjHand();
        $hand->addCard(new Proj('hearts', '10'));
        $hand->addCard(new Proj('diamonds', '5'));
        $state->setBankScore(15);
        $this->assertEquals(15, $state->getBankScore());
    }

    public function testSetBankScore(): void
    {
        $state = new GameState();
        $state->setBankScore(17);
        $this->assertEquals(17, $state->getBankScore());
    }
}