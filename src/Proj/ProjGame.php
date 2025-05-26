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

    public function __construct(ProjDeck $deck, Player $player)
    {
        $this->gameState = new GameState();
        $this->handManager = new ProjHandManager();
        $this->bankStrategy = new BankStrategy();
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
        if ($card) {
            $this->handManager->getCurrentHand()->addCard($card);
            $score = $this->getCurrentScore();

            if ($score > 21) {
                if (!$this->handManager->nextHand()) {

                    if ($this->hasAnyValidHand()) {

                        $this->bankStrategy->playTurn(
                            $this->gameState->getBankHand(),
                            $this->deck,
                            $this->scoreCalculator,
                            $this->handManager->getAllHands()
                        );
                    }

                    $this->gameState->endGame();
                    $this->gameState->evaluateResults(
                        $this->player,
                        $this->handManager->getAllHands(),
                        $this->gameState->getBankHand(),
                        $this->scoreCalculator
                    );
                }
            }
        }
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
        $this->gameState->evaluateResults(
            $this->player,
            $this->handManager->getAllHands(),
            $this->gameState->getBankHand(),
            $this->scoreCalculator
        );
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
        $bankScore = $this->getBankScore();

        $bestPlayerScore = 0;
        foreach ($this->getPlayerHands() as $hand) {
            $handScore = $this->getHandScore($hand);
            if ($handScore <= 21 && $handScore > $bestPlayerScore) {
                $bestPlayerScore = $handScore;
            }
        }

        if ($bestPlayerScore === 0) {
            return 'bank';
        }

        $result = $this->determineHandResult($bestPlayerScore, $bankScore, $this->getPlayerHands()[0]);

        return $result;
    }

    private function determineHandResult(int $playerScore, int $bankScore, ProjHand $hand): string
    {
        if ($bankScore > 21) {
            return 'player';
        }

        $playerHasBlackjack = $this->gameState->hasBlackjack($hand);
        $bankHasBlackjack = $this->gameState->hasBlackjack($this->getBankHand());

        if ($playerHasBlackjack && !$bankHasBlackjack) {
            return 'player';
        }

        if ($playerHasBlackjack && $bankHasBlackjack) {
            return 'push';
        }

        if ($playerScore === $bankScore) {
            return in_array($bankScore, [17, 18, 19]) ? 'bank' : 'push';
        }

        return ($playerScore > $bankScore) ? 'player' : 'bank';
    }
}
