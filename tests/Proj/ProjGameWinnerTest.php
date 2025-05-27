<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjGameWinnerTest extends TestCase
{
    public function testPlayerWinsWithHigherScore(): void
    {
        $playerHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', '10'));
        $playerHand->addCard(new Proj('diamonds', '10'));
        
        $bankHand = new ProjHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '6'));
        
        $winner = $this->determineWinner($playerHand, $bankHand);
        $this->assertEquals('player', $winner);
    }

    public function testBankWinsWithHigherScore(): void
    {
        $playerHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', '10'));
        $playerHand->addCard(new Proj('diamonds', '7'));
        
        $bankHand = new ProjHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '8'));
        
        $winner = $this->determineWinner($playerHand, $bankHand);
        $this->assertEquals('bank', $winner);
    }

    public function testPushWhenScoresEqual(): void
    {
        $playerHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', '10'));
        $playerHand->addCard(new Proj('diamonds', '10'));
        
        $bankHand = new ProjHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '10'));
        
        $winner = $this->determineWinner($playerHand, $bankHand);
        $this->assertEquals('push', $winner);
    }

    public function testPlayerWinsWithBlackjack(): void
    {
        $playerHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', 'ace'));
        $playerHand->addCard(new Proj('diamonds', 'king'));
        
        $bankHand = new ProjHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '7'));
        
        $winner = $this->determineWinner($playerHand, $bankHand);
        $this->assertEquals('player', $winner);
    }

    public function testPlayerWinsWhenBankBusts(): void
    {
        $playerHand = new ProjHand();
        $playerHand->addCard(new Proj('hearts', '10'));
        $playerHand->addCard(new Proj('diamonds', '8'));
        
        $bankHand = new ProjHand();
        $bankHand->addCard(new Proj('clubs', '10'));
        $bankHand->addCard(new Proj('spades', '10'));
        $bankHand->addCard(new Proj('hearts', '2'));
        
        $winner = $this->determineWinner($playerHand, $bankHand);
        $this->assertEquals('player', $winner);
    }

    private function determineWinner(ProjHand $playerHand, ProjHand $bankHand): string
    {
        $deck = new ProjDeck();
        $player = new Player('test');
        $game = new ProjGame($deck, $player);
        
        $game->addHand();
        foreach ($playerHand->getCards() as $card) {
            $game->getCurrentHand()->addCard($card);
        }
        
        foreach ($bankHand->getCards() as $card) {
            $game->getGameState()->getBankHand()->addCard($card);
        }
        
        $game->getGameState()->endGame();
        
        return $game->getWinner();
    }
}