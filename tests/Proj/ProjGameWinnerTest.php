<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjGameWinnerTest extends TestCase
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

    public function testGetWinnerPlayerWins(): void
    {
        $this->game->playerDraw();
        $this->game->playerDraw();
        
        $bankHand = $this->game->getBankHand();
        $bankHand->addCard(new Proj('hearts', '10'));
        $bankHand->addCard(new Proj('diamonds', '6'));
        
        $this->game->playerStop();
        
        $this->assertEquals('player', $this->game->getWinner());
    }

    public function testGetWinnerBankWins(): void
    {
        $this->game->playerDraw();
        $this->game->playerDraw();
        
        $bankHand = $this->game->getBankHand();
        $bankHand->addCard(new Proj('hearts', '10'));
        $bankHand->addCard(new Proj('diamonds', '9'));
        
        $this->game->playerStop();
        
        $this->assertEquals('bank', $this->game->getWinner());
    }

    public function testGetWinnerPush(): void
    {
        $deck = new ProjDeck();
        $deck->setCards([
            new Proj('hearts', '10'),
            new Proj('diamonds', '10'),
            new Proj('clubs', '10'),
            new Proj('spades', '10')
        ]);
        
        $game = new ProjGame($deck, $this->player);
        
        $game->playerDraw();
        $game->playerDraw();
        
        $bankHand = $game->getBankHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '10'));
        
        $game->playerStop();
        
        $this->assertEquals('push', $game->getWinner());
    }

    public function testGetWinnerPlayerBlackjack(): void
    {
        $deck = new ProjDeck();
        $deck->setCards([
            new Proj('hearts', 'ace'),
            new Proj('diamonds', 'king'),
            new Proj('clubs', '10'),
            new Proj('spades', '7')
        ]);
        
        $game = new ProjGame($deck, $this->player);
        
        $game->playerDraw();
        $game->playerDraw();
        
        $bankHand = $game->getBankHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '7'));
        
        $game->playerStop();
        
        $this->assertEquals('player', $game->getWinner());
    }

    public function testGetWinnerBankBust(): void
    {
        $this->game->playerDraw();
        $this->game->playerDraw();
        
        $bankHand = $this->game->getBankHand();
        $bankHand->addCard(new Proj('hearts', '10'));
        $bankHand->addCard(new Proj('diamonds', '10'));
        $bankHand->addCard(new Proj('clubs', '2'));
        
        $this->game->playerStop();
        
        $this->assertEquals('player', $this->game->getWinner());
    }
}