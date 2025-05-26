<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjPlayerDrawTest extends TestCase
{
    public function testPlayerDrawBasic(): void
    {
        $deck = new ProjDeck();
        $player = new Player('test');
        $game = new ProjGame($deck, $player);
        
        $game->playerDraw();
        $this->assertCount(1, $game->getCurrentHand()->getCards());
        
        $game->playerStop();
        $initialCount = count($game->getCurrentHand()->getCards());
        $game->playerDraw();
        $this->assertCount($initialCount, $game->getCurrentHand()->getCards());
    }

    public function testPlayerDrawBust(): void
    {
        $deck = new ProjDeck();
        $deck->setCards([
            new Proj('hearts', '10'),
            new Proj('diamonds', '10'),
            new Proj('clubs', '5')
        ]);
        
        $game = new ProjGame($deck, new Player('test'));
        
        $game->playerDraw();
        $game->playerDraw();
        $game->playerDraw();
        
        $this->assertTrue($game->getGameOver());
    }
}