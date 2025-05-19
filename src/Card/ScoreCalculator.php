<?php

namespace App\Card;

class ScoreCalculator
{
    public function calculate(CardHand $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $score += $this->getCardValue($card, $aces);
        }

        return $this->adjustForAces($score, $aces);
    }

    private function getCardValue(Card $card, int &$aces): int
    {
        return match($card->getValue()) {
            'jack' => 11,
            'queen' => 12,
            'king' => 13,
            'ace' => $this->handleAce($aces),
            default => (int)$card->getValue()
        };
    }

    private function handleAce(int &$aces): int
    {
        $aces++;
        return 1;
    }

    private function adjustForAces(int $score, int $aces): int
    {
        while ($aces > 0 && $score <= 7) {
            $score += 13;
            $aces--;
        }
        return $score;
    }
}
