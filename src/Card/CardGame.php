<?php

namespace App\Card;

/**
 * Represents a card game between a player and the bank.
 *
 * The game follows standard rules where the player draws cards first,
 * then the bank draws cards until it reaches at least 17 points.
 * The goal is to get as close to 21 points without going over.
 */
class CardGame
{
    private GameState $gameState;
    private ScoreCalculator $scoreCalculator;
    private BankStrategy $bankStrategy;

    /**
     * Initializes a new CardGame instance.
     *
     * @param DeckOfCards $deck The deck of cards to use for the game
     */
    public function __construct(DeckOfCards $deck)
    {
        $this->gameState = new GameState();
        $this->scoreCalculator = new ScoreCalculator();
        $this->bankStrategy = new BankStrategy();
        $deck->shuffle();
    }

    /**
     * Draws a card from the deck for the player.
     *
     * @param DeckOfCards $deck The deck to draw from
     * @return void
     */
    public function playerDraw(DeckOfCards $deck): void
    {
        if ($this->gameState->isGameOver()) {
            return;
        }

        $card = $deck->drawCard();
        if ($card) {
            $this->gameState->getPlayerHand()->addCard($card);
            $this->updatePlayerScore();

            if ($this->gameState->getPlayerScore() > 21) {
                $this->gameState->endGame();
            }
        }
    }

    /**
     * Stops the player's turn.
     * Triggers the bank to play its turn.
     *
     * @param DeckOfCards $deck The deck to draw from
     * @return void
     */
    public function playerStop(DeckOfCards $deck): void
    {
        $this->gameState->endGame();
        $this->bankPlay($deck);
    }

    /**
     * Handles the bank's turn to draw cards.
     * The bank will draw until it reaches at least 17 points.
     *
     * @param DeckOfCards $deck The deck to draw from
     * @return void
     */
    private function bankPlay(DeckOfCards $deck): void
    {
        $this->gameState->setBankScore(
            $this->scoreCalculator->calculate($this->gameState->getBankHand())
        );

        while ($this->bankStrategy->shouldDraw(
            $this->gameState->getBankScore(),
            $this->gameState->getPlayerScore()
        )) {
            $card = $deck->drawCard();
            if ($card) {
                $this->gameState->getBankHand()->addCard($card);
                $this->updateBankScore();

                if ($this->gameState->getBankScore() > 21) {
                    break;
                }
            }
        }
    }

    private function updatePlayerScore(): void
    {
        $this->gameState->setPlayerScore(
            $this->scoreCalculator->calculate($this->gameState->getPlayerHand())
        );
    }

    private function updateBankScore(): void
    {
        $this->gameState->setBankScore(
            $this->scoreCalculator->calculate($this->gameState->getBankHand())
        );
    }

    public function getGameOver(): bool
    {
        return $this->gameState->isGameOver();
    }
    public function getPlayerHand(): CardHand
    {
        return $this->gameState->getPlayerHand();
    }
    public function getBankHand(): CardHand
    {
        return $this->gameState->getBankHand();
    }
    public function getPlayerScore(): int
    {
        return $this->gameState->getPlayerScore();
    }
    public function getBankScore(): int
    {
        return $this->gameState->getBankScore();
    }

    public function getWinner(): string
    {
        if ($this->gameState->getPlayerScore() > 21) {
            return 'bank';
        }
        if ($this->gameState->getBankScore() > 21) {
            return 'player';
        }
        return $this->gameState->getBankScore() >= $this->gameState->getPlayerScore()
            ? 'bank'
            : 'player';
    }
}
