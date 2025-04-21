<?php

namespace App\Card;

class CardGame
{
    private CardHand $playerHand;
    private CardHand $bankHand;
    private bool $gameOver;
    private int $playerScore;
    private int $bankScore;

    public function __construct(DeckOfCards $deck)
    {
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
        $this->gameOver = false;
        $this->playerScore = 0;
        $this->bankScore = 0;
        $deck->shuffle();
    }

    public function playerDraw(DeckOfCards $deck): void
    {
        if (!$this->gameOver) {
            $card = $deck->drawCard();
            if ($card) {
                $this->playerHand->addCard($card);
                $this->playerScore = $this->calculatePlayerScore($this->playerHand);
                
                if ($this->playerScore > 21) {
                    $this->gameOver = true;
                }
            }
        }
    }

    public function playerStop(DeckOfCards $deck): void
    {
        $this->gameOver = true;
        $this->bankPlay($deck);
    }

    private function bankPlay(DeckOfCards $deck): void
    {
        $this->bankScore = $this->calculateBankScore($this->bankHand);
        
        while ($this->bankScore < 17 && $this->playerScore <= 21) {
            $card = $deck->drawCard();
            if ($card) {
                $this->bankHand->addCard($card);
                $this->bankScore = $this->calculateBankScore($this->bankHand);
                
                if ($this->bankScore > 21) {
                    break;
                }
            }
        }
    }

    private function calculatePlayerScore(CardHand $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $value = $card->getValue();

            if (in_array($value, ['jack'])) {
                $score += 11;
            } elseif (in_array($value, ['queen'])) {
                $score += 12;
            } elseif (in_array($value, ['king'])) {
                $score += 13;
            } elseif ($value === 'ace') {
                $score += 1;
                $aces++;
            } else {
                $score += (int)$value;
            }
        }

        while ($aces > 0 && $score <= 11) {
            $score += 13;
            $aces--;
        }

        return $score;
    }

    private function calculateBankScore(CardHand $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $value = $card->getValue();
            
            if (in_array($value, ['jack'])) {
                $score += 11;
            } elseif (in_array($value, ['queen'])) {
                $score += 12;
            } elseif (in_array($value, ['king'])) {
                $score += 13;
            } elseif ($value === 'ace') {
                $score += 1;
                $aces++;
            } else {
                $score += (int)$value;
            }
        }

        while ($aces > 0 && $score <= 7) {
            $score += 13;
            $aces--;
        }

        return $score;
    }

    public function getGameOver(): bool
    {
        return $this->gameOver;
    }

    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    public function getBankHand(): CardHand
    {
        return $this->bankHand;
    }

    public function getPlayerScore(): int
    {
        return $this->playerScore;
    }

    public function getBankScore(): int
    {
        return $this->bankScore;
    }

    public function getWinner(): string
    {
        if ($this->playerScore > 21) {
            return 'bank';
        }

        if ($this->bankScore > 21) {
            return 'player';
        }

        if ($this->bankScore >= $this->playerScore) {
            return 'bank';
        }

        return 'player';
    }
}