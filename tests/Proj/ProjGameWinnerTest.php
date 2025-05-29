<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjGameWinnerTest extends TestCase
{
    public function testAllWinConditions(): void
    {
        $this->assertEquals('player', $this->determineWinner(
            $this->createHand(['10', '10']), 
            $this->createHand(['10', '6'])
        ));

        $this->assertEquals('bank', $this->determineWinner(
            $this->createHand(['10', '7']),
            $this->createHand(['10', '8'])
        ));

        $this->assertEquals('push', $this->determineWinner(
            $this->createHand(['10', '10']),
            $this->createHand(['10', '10'])
        ));

        $this->assertEquals('blackjack', $this->determineWinner(
            $this->createHand(['ace', 'king']),
            $this->createHand(['10', '7'])
        ));

        $this->assertEquals('player', $this->determineWinner(
            $this->createHand(['10', '8']),
            $this->createHand(['10', '10', '2'])
        ));

        $this->assertEquals('bank', $this->determineWinner(
            $this->createHand(['10', '7']),
            $this->createHand(['10', '7'])
        ));
    }

    private function createHand(array $values): ProjHand
    {
        $hand = new ProjHand();
        foreach ($values as $value) {
            $hand->addCard(new Proj('hearts', $value));
        }
        return $hand;
    }

    private function determineWinner(ProjHand $playerHand, ProjHand $bankHand): string
    {
        $deck = new ProjDeck();
        $player = new Player('test');
        $game = new ProjGame($deck, $player);
        
        foreach ($playerHand->getCards() as $card) {
            $game->getCurrentHand()->addCard($card);
        }
        
        foreach ($bankHand->getCards() as $card) {
            $game->getBankHand()->addCard($card);
        }
        
        $game->getGameState()->endGame();
        return $game->getWinner();
    }
}