<?php

namespace App\Proj;

class Proj
{
    protected string $suit;
    protected string $value;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function matches(string $suit, string $value): bool
    {
        return $this->suit === $suit && $this->value === $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "{$this->value} of {$this->suit}";
    }
}
