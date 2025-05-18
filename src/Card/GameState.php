<?php
namespace App\Card;

class GameState
{
    private CardHand $playerHand;
    private CardHand $bankHand;
    private int $playerScore = 0;
    private int $bankScore = 0;
    private bool $gameOver = false;

    public function __construct()
    {
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
    }

    public function getPlayerHand(): CardHand { return $this->playerHand; }
    public function getPlayerScore(): int { return $this->playerScore; }
    public function setPlayerScore(int $score): void { $this->playerScore = $score; }
    public function getBankHand(): CardHand { return $this->bankHand; }
    public function getBankScore(): int { return $this->bankScore; }
    public function setBankScore(int $score): void { $this->bankScore = $score; }
    public function isGameOver(): bool { return $this->gameOver; }
    public function endGame(): void { $this->gameOver = true; }
}