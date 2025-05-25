<?php

namespace App\Proj;

class ScoreCalculator
{
    public function calculate(ProjHand $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $value = $this->getCardValue($card, $aces);
            $score += $value;
        }

        while ($aces > 0 && $score <= 11) {
            $score += 10;
            $aces--;
        }

        return $score;
    }

    private function getCardValue(Proj $card, int &$aces): int
    {
        $value = $card->getValue();
        
        if (in_array($value, ['jack', 'queen', 'king'])) {
            return 10;
        } elseif ($value === 'ace') {
            $aces++;
            return 1;
        }
        
        return (int)$value;
    }
}