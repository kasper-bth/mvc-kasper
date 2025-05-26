<?php

namespace App\Proj;

class BankStrategy
{
    public function shouldDraw(int $bankScore, int $playerScore): bool
    {
        return $bankScore < 17 && $playerScore <= 21;
    }

    public function playTurn(ProjHand $bankHand, ProjDeck $deck, ScoreCalculator $calculator, array $playerHands): void
    {
        $bankScore = $calculator->calculate($bankHand);
        $playerMaxScore = $this->getHighestPlayerScore($playerHands, $calculator);

        while ($this->shouldDraw($bankScore, $playerMaxScore)) {
            $card = $deck->drawCard();
            if ($card) {
                $bankHand->addCard($card);
                $bankScore = $calculator->calculate($bankHand);

                if ($bankScore > 21) {
                    break;
                }
            }
        }
    }

    private function getHighestPlayerScore(array $playerHands, ScoreCalculator $calculator): int
    {
        $maxScore = 0;
        foreach ($playerHands as $hand) {
            $score = $calculator->calculate($hand);
            if ($score <= 21 && $score > $maxScore) {
                $maxScore = $score;
            }
        }
        return $maxScore;
    }
}
