<?php

namespace App\Proj;

class GameState
{
    private ProjHand $bankHand;
    private int $bankScore = 0;
    private bool $gameOver = false;
    private bool $initialDeal = true;
    private array $handResults = [];

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
        return $this->bankScore;
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

    public function markInitialDealComplete(): void
    {
        $this->initialDeal = false;
    }

    public function setHandResults(array $results): void
    {
        $this->handResults = $results;
    }

    public function getHandResults(): array
    {
        return $this->handResults;
    }

    public function isInitialDeal(): bool
    {
        return $this->initialDeal;
    }

    public function isBusted(int $score): bool
    {
        return $score > 21;
    }
}
