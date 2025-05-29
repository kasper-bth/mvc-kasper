<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

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

    public function testInitialState(): void
    {
        $this->assertCount(1, $this->game->getPlayerHands());
        $this->assertFalse($this->game->getGameOver());
        $this->assertEquals(0, $this->game->getCurrentHandIndex());
    }

    public function testPlayerActions(): void
    {
        $this->game->playerDraw();
        $this->assertCount(1, $this->game->getCurrentHand()->getCards());
        
        $this->game->playerStop();
        $initialCount = count($this->game->getCurrentHand()->getCards());
        $this->game->playerDraw();
        $this->assertCount($initialCount, $this->game->getCurrentHand()->getCards());
    }

    public function testHandManagement(): void
    {
        $this->assertTrue($this->game->addHand());
        $this->assertCount(2, $this->game->getPlayerHands());
        
        $this->game->playerStop();
        $this->assertEquals(1, $this->game->getCurrentHandIndex());
    }

    public function testScoring(): void
    {
        $hand = new ProjHand();
        $hand->addCard(new Proj('hearts', '10'));
        $hand->addCard(new Proj('diamonds', '5'));
        $this->assertEquals(15, $this->game->getHandScore($hand));
    }

    public function testGameComponents(): void
    {
        $this->assertInstanceOf(ProjDeck::class, $this->game->getDeck());
        $this->assertInstanceOf(ProjHand::class, $this->game->getBankHand());
        $this->assertEquals(0, $this->game->getBankScore());
    }

    public function testDetermineResults(): void
    {
        $playerHand = $this->game->getCurrentHand();
        $playerHand->addCard(new Proj('hearts', '10'));
        $playerHand->addCard(new Proj('diamonds', '10'));
        
        $bankHand = $this->game->getBankHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '7'));
        
        $this->game->getGameState()->setBankScore(17);
        $this->game->getGameState()->endGame();
        
        $this->assertEquals('player', $this->game->getWinner());
    }

    public function testGetAllResults(): void
    {
        $this->game->addHand();
        
        $hand1 = $this->game->getPlayerHands()[0];
        $hand1->addCard(new Proj('hearts', '10'));
        $hand1->addCard(new Proj('diamonds', '10'));
        
        $hand2 = $this->game->getPlayerHands()[1];
        $hand2->addCard(new Proj('clubs', 'ace'));
        $hand2->addCard(new Proj('spades', 'king'));
        
        $bankHand = $this->game->getBankHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '7'));
        
        $this->game->getGameState()->setBankScore(17);
        $this->game->getGameState()->endGame();
        
        $results = $this->game->getAllResults();
        
        $this->assertCount(2, $results);
        $this->assertEquals('player', $results[0]);
        $this->assertEquals('blackjack', $results[1]);
    }

    public function testPlayBankTurn(): void
    {
        $mockBankStrategy = $this->createMock(BankStrategy::class);
        $mockBankStrategy->expects($this->once())
            ->method('playTurn');
        
        $game = new ProjGame(new ProjDeck(), new Player('test'));
        $reflection = new ReflectionClass($game);
        $property = $reflection->getProperty('bankStrategy');
        $property->setAccessible(true);
        $property->setValue($game, $mockBankStrategy);
        
        $method = $reflection->getMethod('playBankTurn');
        $method->setAccessible(true);
        $method->invoke($game);
    }

    public function testGameWithBets(): void
{
    $this->player->placeBet(100);
    $this->assertEquals(900, $this->player->getBankroll());
    
    $hand = $this->game->getCurrentHand();
    $hand->addCard(new Proj('hearts', '10'));
    $hand->addCard(new Proj('diamonds', '7'));
    
    $bankHand = $this->game->getBankHand();
    $bankHand->addCard(new Proj('clubs', '10'));
    $bankHand->addCard(new Proj('spades', '6'));
    
    $this->game->playerStop();
    
    $this->assertEquals(1100, $this->player->getBankroll());
    $this->assertTrue($this->game->getGameOver());
}

    public function testBlackjackWithBet(): void
    {
        $this->player->placeBet(100);
        $this->assertEquals(900, $this->player->getBankroll());
        
        $hand = $this->game->getCurrentHand();
        $hand->addCard(new Proj('hearts', 'ace'));
        $hand->addCard(new Proj('diamonds', 'king'));
        
        $bankHand = $this->game->getBankHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '7'));
        
        $this->game->playerStop();
        
        $this->assertEquals(1150, $this->player->getBankroll());
        $this->assertEquals(150, $this->player->getTotalWins());
    }
}