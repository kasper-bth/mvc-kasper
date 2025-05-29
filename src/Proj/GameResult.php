<?php

namespace App\Proj;

class GameResult
{
    private ScoreCalculator $scoreCalculator;

    public function __construct()
    {
        $this->scoreCalculator = new ScoreCalculator();
    }

    public function evaluateResults(Player $player, array $playerHands, ProjHand $bankHand, bool $isInitialDeal): array
    {
        $bankScore = $this->scoreCalculator->calculate($bankHand);
        $results = [];

        foreach ($playerHands as $index => $hand) {
            $handScore = $this->scoreCalculator->calculate($hand);
            $result = $this->determineResult($handScore, $bankScore, $hand, $bankHand, $isInitialDeal);
            $results[$index] = $result;
            $this->applyResultToPlayer($player, $result, $index);
        }

        return $results;
    }

    public function determineResult(int $playerScore, int $bankScore, ProjHand $hand, ProjHand $bankHand, bool $isInitialDeal): string
    {
        if ($this->hasBlackjack($hand, $isInitialDeal)) {
            return $this->getBlackjackResult($hand, $bankHand, $isInitialDeal);
        }

        if ($playerScore > 21) {
            return 'bank';
        }
        if ($bankScore > 21) {
            return 'player';
        }
        if ($playerScore === $bankScore) {
            return $bankScore >= 17 && $bankScore <= 19 ? 'bank' : 'push';
        }

        return $playerScore > $bankScore ? 'player' : 'bank';
    }

    public function hasBlackjack(ProjHand $hand, bool $isInitialDeal): bool
    {
        if (!$isInitialDeal || $hand->getNumberCards() !== 2) {
            return false;
        }

        $values = $hand->getValues();
        $hasAce = in_array('ace', $values, true);
        $hasTenValue = !empty(array_intersect(['10', 'jack', 'queen', 'king'], $values));

        return $hasAce && $hasTenValue;
    }

    private function getBlackjackResult(ProjHand $hand, ProjHand $bankHand, bool $isInitialDeal): string
    {
        $playerHasBlackjack = $this->hasBlackjack($hand, $isInitialDeal);
        $bankHasBlackjack = $this->hasBlackjack($bankHand, $isInitialDeal);

        if ($playerHasBlackjack && $bankHasBlackjack) {
            return 'push';
        }

        return $playerHasBlackjack ? 'blackjack' : 'bank';
    }

    private function applyResultToPlayer(Player $player, string $result, int $handIndex): void
    {
        match ($result) {
            'player'    => $player->win(1.0, $handIndex),
            'blackjack' => $player->win(1.5, $handIndex),
            'bank'      => $player->lose($handIndex),
            default     => $player->push($handIndex),
        };
    }

    public function compareScoresWithPushRule(Player $player, int $handScore, int $bankScore, int $handIndex): void
    {
        if ($handScore > $bankScore) {
            $player->win(1.0, $handIndex);
            return;
        }

        if ($bankScore > $handScore) {
            $player->lose($handIndex);
            return;
        }

        if ($bankScore >= 17 && $bankScore <= 19) {
            $player->lose($handIndex);
            return;
        }

        $player->push($handIndex);
    }
}
