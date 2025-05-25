<?php

namespace App\Proj;

class Player
{
    private string $nickname;
    private int $bankroll;
    private int $totalWins = 0;
    private int $totalLosses = 0;
    private int $currentBet = 0;

    public function __construct(string $nickname, int $initialBankroll = 1000)
    {
        $this->nickname = $nickname;
        $this->bankroll = $initialBankroll;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getBankroll(): int
    {
        return $this->bankroll;
    }

    public function placeBet(int $amount): bool
    {
        if ($amount <= 0 || $amount > $this->bankroll) {
            return false;
        }
        $this->currentBet = $amount;
        $this->bankroll -= $amount;
        return true;
    }

    public function win(float $multiplier = 1.0): void {
        $winnings = (int)($this->currentBet * $multiplier);
        $this->bankroll += $this->currentBet + $winnings;
        $this->totalWins += $winnings;
        $this->currentBet = 0;
    }

    public function lose(): void {
        $this->totalLosses += $this->currentBet;
        $this->currentBet = 0;
    }

    public function push(): void
    {
        $this->bankroll += $this->currentBet;
        $this->currentBet = 0;
    }

    public function getCurrentBet(): int
    {
        return $this->currentBet;
    }

    public function getTotalWins(): int {
        return $this->totalWins;
    }

    public function getTotalLosses(): int {
        return $this->totalLosses;
    }

    public function updateBet(int $newAmount): bool
    {
        $this->bankroll += $this->currentBet;
        
        if ($newAmount <= 0 || $newAmount > $this->bankroll) {
            $this->bankroll -= $this->currentBet;
            return false;
        }
        
        $this->currentBet = $newAmount;
        $this->bankroll -= $newAmount;
        return true;
    }
}