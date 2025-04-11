<?php

namespace App\Card;

class CardGraphic extends Card
{
    private const SUIT_SYMBOLS = [
        'hearts' => '♥',
        'diamonds' => '♦',
        'clubs' => '♣',
        'spades' => '♠'
    ];

    private const VALUE_SYMBOLS = [
        'ace' => 'A',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        'jack' => 'J',
        'queen' => 'Q',
        'king' => 'K'
    ];

    public function getAsString(): string
    {
        $symbol = self::VALUE_SYMBOLS[$this->value] ?? $this->value;
        $suitSymbol = self::SUIT_SYMBOLS[$this->suit] ?? $this->suit;
        
        return $symbol . $suitSymbol;
    }

    public function getColor(): string
    {
        return in_array($this->suit, ['hearts', 'diamonds']) ? 'red' : 'black';
    }
}
