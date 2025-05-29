<?php

namespace App\Proj;

class Player
{
    private string $nickname;
    private int $bankroll;
    private int $totalWins = 0;
    private int $totalLosses = 0;
    private array $handBets = [];

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

    public function canPlaceBet(int $amount, int $numHands = 1): bool
    {
        return ($amount * $numHands) <= $this->bankroll;
    }

    public function placeBet(int $amount, int $handIndex = 0): bool
    {
        if ($amount <= 0 || $amount > $this->bankroll) {
            return false;
        }
        $this->handBets[$handIndex] = $amount;
        $this->bankroll -= $amount;
        return true;
    }

    public function clearBets(): void
    {
        $this->handBets = [];
    }

    public function getCurrentBet(): int
    {
        return array_sum($this->handBets);
    }

    public function getBetForHand(int $handIndex): int
    {
        return $this->handBets[$handIndex] ?? 0;
    }

    public function win(float $multiplier = 1.0, int $handIndex = 0): void
    {
        $bet = $this->handBets[$handIndex] ?? 0;
        $winnings = (int)round($bet * $multiplier);
        $this->bankroll += $bet + $winnings;
        $this->totalWins += $winnings;
        unset($this->handBets[$handIndex]);
    }

    public function lose(int $handIndex = 0): void
    {
        $bet = $this->handBets[$handIndex] ?? 0;
        $this->totalLosses += $bet;
    }

    public function push(int $handIndex = 0): void
    {
        $bet = $this->handBets[$handIndex] ?? 0;
        $this->bankroll += $bet;
        unset($this->handBets[$handIndex]);
    }

    public function getTotalWins(): int
    {
        return $this->totalWins;
    }

    public function getTotalLosses(): int
    {
        return $this->totalLosses;
    }
}
