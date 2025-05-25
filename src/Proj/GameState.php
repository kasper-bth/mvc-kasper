<?php

namespace App\Proj;

class GameState
{
    private ProjHand $bankHand;
    private ScoreCalculator $scoreCalculator;
    private int $bankScore = 0;
    private bool $gameOver = false;

    public function __construct()
    {
        $this->bankHand = new ProjHand();
    }

    public function getBankHand(): ProjHand
    {
        return $this->bankHand;
    }

    public function getBankScore(): int
    {
        return $this->scoreCalculator->calculate($this->bankHand);
    }

    public function setBankScore(int $score): void
    {
        $this->bankScore = $score;
    }

    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    public function endGame(): void
    {
        $this->gameOver = true;
    }

    public function evaluateResults(Player $player, array $playerHands, ProjHand $bankHand, ScoreCalculator $calculator): void
    {
        $bankScore = $calculator->calculate($bankHand);
        
        foreach ($playerHands as $hand) {
            $handScore = $calculator->calculate($hand);
            
            if ($handScore > 21) {
                $player->lose();
            } elseif ($bankScore > 21) {
                $player->win();
            } elseif ($this->hasBlackjack($hand) && !$this->hasBlackjack($bankHand)) {
                $player->win(1.5);
            } elseif ($handScore > $bankScore) {
                $player->win();
            } elseif ($bankScore > $handScore) {
                $player->lose();
            } else {
                $player->push();
            }
        }
    }

    public function hasBlackjack(ProjHand $hand): bool
    {
        if ($hand->getNumberCards() != 2) {
            return false;
        }
        
        $values = $hand->getValues();
        return (in_array('ace', $values) && 
                (in_array('10', $values) || 
                in_array('jack', $values) || 
                in_array('queen', $values) || 
                in_array('king', $values)));
    }
}