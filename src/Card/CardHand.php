<?php
// src/Card/CardHand.php
namespace App\Card;

class CardHand
{
    /** @var Card[] */
    private array $cards = [];

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getNumberCards(): int
    {
        return count($this->cards);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getValue();
        }
        return $values;
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
