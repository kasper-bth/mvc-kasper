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
    /** @var CardHand The player's hand of cards */
    private CardHand $playerHand;

    /** @var CardHand The bank's hand of cards */
    private CardHand $bankHand;

    /** @var bool Flag indicating if the game has ended */
    private bool $gameOver;

    /** @var int The player's current score */
    private int $playerScore;

    /** @var int The bank's current score */
    private int $bankScore;

    /**
     * Initializes a new CardGame instance.
     *
     * @param DeckOfCards $deck The deck of cards to use for the game
     */
    public function __construct(DeckOfCards $deck)
    {
        $this->playerHand = new CardHand();
        $this->bankHand = new CardHand();
        $this->gameOver = false;
        $this->playerScore = 0;
        $this->bankScore = 0;
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
        if (!$this->gameOver) {
            $card = $deck->drawCard();
            if ($card) {
                $this->playerHand->addCard($card);
                $this->playerScore = $this->calculateScore($this->playerHand);

                if ($this->playerScore > 21) {
                    $this->gameOver = true;
                }
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
        $this->gameOver = true;
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
        $this->bankScore = $this->calculateScore($this->bankHand);

        while ($this->bankScore < 17 && $this->playerScore <= 21) {
            $card = $deck->drawCard();
            if ($card) {
                $this->bankHand->addCard($card);
                $this->bankScore = $this->calculateScore($this->bankHand);

                if ($this->bankScore > 21) {
                    break;
                }
            }
        }
    }

    /**
     * Calculates the score for a given hand of cards.
     *
     * @param CardHand $hand The hand to calculate score for
     * @return int The calculated score
     */
    private function calculateScore(CardHand $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $value = $card->getValue();

            if (in_array($value, ['jack'])) {
                $score += 11;
                continue;
            }
            if (in_array($value, ['queen'])) {
                $score += 12;
                continue;
            }
            if (in_array($value, ['king'])) {
                $score += 13;
                continue;
            }
            if ($value === 'ace') {
                $score += 1;
                $aces++;
                continue;
            }
            $score += (int)$value;
        }

        while ($aces > 0 && $score <= 7) {
            $score += 13;
            $aces--;
        }

        return $score;
    }

    /**
     * Checks if the game has ended.
     *
     * @return bool True if the game is over, false otherwise
     */
    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    /**
     * Gets the player's current hand.
     *
     * @return CardHand The player's hand
     */
    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    /**
     * Gets the bank's current hand.
     *
     * @return CardHand The bank's hand
     */
    public function getBankHand(): CardHand
    {
        return $this->bankHand;
    }

    /**
     * Gets the player's current score.
     *
     * @return int The player's score
     */
    public function getPlayerScore(): int
    {
        return $this->playerScore;
    }

    /**
     * Gets the bank's current score.
     *
     * @return int The bank's score
     */
    public function getBankScore(): int
    {
        return $this->bankScore;
    }

    /**
     * Determines the winner of the game.
     *
     * @return string 'player' if the player wins, 'bank' if the bank wins
     */
    public function getWinner(): string
    {
        if ($this->playerScore > 21) {
            return 'bank';
        }

        if ($this->bankScore > 21) {
            return 'player';
        }

        if ($this->bankScore >= $this->playerScore) {
            return 'bank';
        }

        return 'player';
    }
}
