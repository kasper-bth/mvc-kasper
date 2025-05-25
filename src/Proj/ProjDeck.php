<?php

namespace App\Proj;

class ProjDeck
{
    private const SUITS = ['hearts', 'diamonds', 'clubs', 'spades'];
    private const VALUES = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
    private const SORT_ORDER = ['clubs', 'diamonds', 'hearts', 'spades'];

    private array $cards = [];

    public function __construct()
    {
        $this->initializeDeck();
    }

    private function initializeDeck(): void
    {
        foreach (self::SUITS as $suit) {
            foreach (self::VALUES as $value) {
                $this->cards[] = new ProjGraphic($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function drawCard(): ?Proj
    {
        return array_pop($this->cards);
    }

    public function getSortedCards(): array
    {
        $cards = $this->cards;
        usort($cards, function (Proj $card1, Proj $card2) {
            $suitOrder = array_flip(self::SORT_ORDER);
            $valueOrder = array_flip(self::VALUES);

            $suitCompare = $suitOrder[$card1->getSuit()] <=> $suitOrder[$card2->getSuit()];
            if ($suitCompare !== 0) {
                return $suitCompare;
            }
            return $valueOrder[$card1->getValue()] <=> $valueOrder[$card2->getValue()];
        });

        return $cards;
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
