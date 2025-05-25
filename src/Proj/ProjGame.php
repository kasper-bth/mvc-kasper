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

            if ($score > 21 && !$this->handManager->nextHand()) {
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

    public function playerStop(): void
    {
        if ($this->handManager->nextHand()) {
            return;
        }
        
        $this->bankStrategy->playTurn(
            $this->gameState->getBankHand(),
            $this->deck,
            $this->scoreCalculator,
            $this->handManager->getAllHands()
        );
        
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
        $playerWins = 0;
        $bankWins = 0;

        foreach ($this->getPlayerHands() as $hand) {
            $handScore = $this->getHandScore($hand);
            
            if ($handScore > 21) {
                $bankWins++;
            } elseif ($bankScore > 21) {
                $playerWins++;
            } elseif ($this->gameState->hasBlackjack($hand) && !$this->gameState->hasBlackjack($this->getBankHand())) {
                $playerWins++;
            } elseif ($handScore > $bankScore) {
                $playerWins++;
            } elseif ($bankScore > $handScore) {
                $bankWins++;
            }
        }

        if ($playerWins > $bankWins) {
            return 'player';
        } elseif ($bankWins > $playerWins) {
            return 'bank';
        }
        return 'push';
    }
}