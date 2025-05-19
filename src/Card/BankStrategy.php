<?php

namespace App\Card;

class BankStrategy
{
    public function shouldDraw(int $bankScore, int $playerScore): bool
    {
        return $bankScore < 17 && $playerScore <= 21;
    }
}
