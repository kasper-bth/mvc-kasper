<?php

namespace App\Card;

class DeckOfCards
{
    /** @var Card[] */
    protected array $cards = [];

    public function __construct()
    {
        $this->initializeDeck();
    }

    protected function initializeDeck(): void
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function drawCard(): ?Card
    {
        return array_pop($this->cards);
    }

    public function getNumberCards(): int
    {
        return count($this->cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getString(): array
    {
        $strings = [];
        foreach ($this->cards as $card) {
            $strings[] = $card->getAsString();
        }
        return $strings;
    }
}