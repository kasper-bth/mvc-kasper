<?php

namespace App\Proj;

class ProjGame
{
    private GameState $gameState;
    private ProjHandManager $handManager;
    private BankStrategy $bankStrategy;
    private Player $player;
    private ProjDeck $deck;
    private ScoreCalculator $scoreCalculator;
    private GameResult $gameResult;

    public function __construct(ProjDeck $deck, Player $player)
    {
        $this->gameState = new GameState();
        $this->handManager = new ProjHandManager();
        $this->bankStrategy = new BankStrategy();
        $this->gameResult = new GameResult();
        $this->scoreCalculator = new ScoreCalculator();
        $this->player = $player;
        $this->deck = $deck;
        $this->deck->shuffle();
    }

    public function playerDraw(): void
    {
        if ($this->gameState->isGameOver()) {
            return;
        }

        $card = $this->deck->drawCard();
        if (!$card) {
            return;
        }

        $this->handManager->getCurrentHand()->addCard($card);
        $score = $this->getCurrentScore();

        if ($score <= 21) {
            return;
        }

        if ($this->handManager->nextHand()) {
            return;
        }

        $this->handleGameEnd();
    }

    private function handleGameEnd(): void
    {
        if ($this->hasAnyValidHand()) {
            $this->playBankTurn();
        }

        $this->gameState->endGame();
        $this->evaluateFinalResults();
    }

    private function playBankTurn(): void
    {
        $this->bankStrategy->playTurn(
            $this->gameState->getBankHand(),
            $this->deck,
            $this->scoreCalculator,
            $this->handManager->getAllHands()
        );
    }

    private function evaluateFinalResults(): void
    {
        $results = $this->gameResult->evaluateResults(
            $this->player,
            $this->handManager->getAllHands(),
            $this->gameState->getBankHand(),
            $this->gameState->isInitialDeal()
        );
        $this->gameState->setHandResults($results);
    }

    private function hasAnyValidHand(): bool
    {
        foreach ($this->getPlayerHands() as $hand) {
            if ($this->getHandScore($hand) <= 21) {
                return true;
            }
        }
        return false;
    }


    public function playerStop(): void
    {
        if ($this->handManager->nextHand()) {
            return;
        }

        if ($this->hasAnyValidHand()) {
            $this->bankStrategy->playTurn(
                $this->gameState->getBankHand(),
                $this->deck,
                $this->scoreCalculator,
                $this->handManager->getAllHands()
            );
        }

        $this->gameState->endGame();
        $this->evaluateFinalResults();
    }

    public function addHand(): bool
    {
        return $this->handManager->addHand();
    }

    public function getCurrentScore(): int
    {
        return $this->scoreCalculator->calculate(
            $this->handManager->getCurrentHand()
        );
    }

    public function getPlayerHands(): array
    {
        return $this->handManager->getAllHands();
    }

    public function getCurrentHand(): ProjHand
    {
        return $this->handManager->getCurrentHand();
    }

    public function getCurrentHandIndex(): int
    {
        return $this->handManager->getCurrentIndex();
    }

    public function getHandScore(ProjHand $hand): int
    {
        return $this->scoreCalculator->calculate($hand);
    }

    public function getDeck(): ProjDeck
    {
        return $this->deck;
    }

    public function getGameOver(): bool
    {
        return $this->gameState->isGameOver();
    }

    public function getBankHand(): ProjHand
    {
        return $this->gameState->getBankHand();
    }

    public function getGameState(): GameState
    {
        return $this->gameState;
    }

    public function getBankScore(): int
    {
        return $this->scoreCalculator->calculate($this->gameState->getBankHand());
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getWinner(): string
    {
        if ($this->allHandsBusted()) {
            return 'bank';
        }

        $bankScore = $this->getBankScore();
        $bestPlayerScore = 0;

        foreach ($this->getPlayerHands() as $hand) {
            $handScore = $this->getHandScore($hand);
            if ($handScore <= 21 && $handScore > $bestPlayerScore) {
                $bestPlayerScore = $handScore;
            }
        }

        return $this->determineHandResult($bestPlayerScore, $bankScore, $this->getPlayerHands()[0]);
    }

    public function getAllResults(): array
    {
        $results = [];
        $bankScore = $this->getBankScore();

        foreach ($this->getPlayerHands() as $hand) {
            $handScore = $this->getHandScore($hand);
            $results[] = $this->determineHandResult($handScore, $bankScore, $hand);
        }

        return $results;
    }

    private function allHandsBusted(): bool
    {
        foreach ($this->getPlayerHands() as $hand) {
            if ($this->getHandScore($hand) <= 21) {
                return false;
            }
        }
        return true;
    }

    private function determineHandResult(int $playerScore, int $bankScore, ProjHand $hand): string
    {
        return $this->gameResult->determineResult(
            $playerScore,
            $bankScore,
            $hand,
            $this->getBankHand(),
            $this->gameState->isInitialDeal()
        );
    }
}
