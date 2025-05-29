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

    /**
     * Get the sum of all current bets as integer
     * @return int Sum of all bets
     */
    public function getCurrentBet(): int
    {
        return (int)array_sum($this->handBets);
    }

    public function getBetForHand(int $handIndex): int
    {
        return $this->handBets[$handIndex] ?? 0;
    }

    /**
     * Process a win for a specific hand
     * @param float $multiplier Win multiplier (1.0 for normal win, 1.5 for blackjack)
     * @param int $handIndex Index of the hand that won
     * @return void
     */
    public function win(float $multiplier = 1.0, int $handIndex = 0): void
    {
        $bet = $this->handBets[$handIndex] ?? 0;
        $winnings = (int)round($bet * $multiplier);
        $this->bankroll += $bet + $winnings;
        $this->totalWins += $winnings;
        unset($this->handBets[$handIndex]);
    }

    /**
     * Process a loss for a specific hand
     * @param int $handIndex Index of the hand that lost
     * @return void
     */
    public function lose(int $handIndex = 0): void
    {
        $bet = $this->handBets[$handIndex] ?? 0;
        $this->totalLosses += $bet;
        unset($this->handBets[$handIndex]);
    }

    /**
     * Process a push (tie) for a specific hand
     * @param int $handIndex Index of the hand that pushed
     * @return void
     */
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
