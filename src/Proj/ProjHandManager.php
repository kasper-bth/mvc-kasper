<?php

namespace App\Proj;

class ProjHandManager
{
    private array $hands;
    private int $currentIndex = 0;

    public function __construct()
    {
        $this->hands = [new ProjHand()];
    }

    public function addHand(): bool
    {
        if (count($this->hands) >= 3) {
            return false;
        }
        $this->hands[] = new ProjHand();
        return true;
    }

    public function getCurrentHand(): ProjHand
    {
        return $this->hands[$this->currentIndex];
    }

    public function nextHand(): bool
    {
        $this->currentIndex++;
        return $this->currentIndex < count($this->hands);
    }

    public function getAllHands(): array
    {
        return $this->hands;
    }

    public function getCurrentIndex(): int
    {
        return $this->currentIndex;
    }
}