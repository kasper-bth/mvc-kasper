<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class GameResultTest extends TestCase
{
    private GameResult $gameResult;
    private Player $player;

    protected function setUp(): void
    {
        $this->gameResult = new GameResult();
        $this->player = new Player('test');
    }

    public function testHasBlackjack(): void
    {
        $hand = $this->createBlackjackHand();
        $this->assertTrue($this->gameResult->hasBlackjack($hand, true));

        $hand->addCard(new Proj('spades', '2'));
        $this->assertFalse($this->gameResult->hasBlackjack($hand, true));

        $this->assertFalse($this->gameResult->hasBlackjack(
            $this->createBlackjackHand(),
            false
        ));
    }

    public function testDetermineResult(): void
    {
        $hand = $this->createHand(['10', '7']);
        $bankHand = $this->createHand(['10', '7']);
        
        $result = $this->gameResult->determineResult(17, 16, $hand, $bankHand, false);
        $this->assertEquals('player', $result);
        
        $result = $this->gameResult->determineResult(16, 17, $hand, $bankHand, false);
        $this->assertEquals('bank', $result);
        
        $result = $this->gameResult->determineResult(20, 20, $hand, $bankHand, false);
        $this->assertEquals('push', $result);
        
        $result = $this->gameResult->determineResult(17, 17, $hand, $bankHand, false);
        $this->assertEquals('bank', $result);
        
        $blackjackHand = $this->createBlackjackHand();
        $result = $this->gameResult->determineResult(21, 17, $blackjackHand, $bankHand, true);
        $this->assertEquals('blackjack', $result);
    }

    public function testCompareScoresWithPushRule(): void
    {
        $this->player->placeBet(10);
        $this->gameResult->compareScoresWithPushRule($this->player, 18, 17, 0);
        $this->assertEquals(1010, $this->player->getBankroll());

        $this->player = new Player('test');
        $this->player->placeBet(10);
        $this->gameResult->compareScoresWithPushRule($this->player, 17, 17, 0);
        $this->assertEquals(990, $this->player->getBankroll());

        $this->player = new Player('test');
        $this->player->placeBet(10);
        $this->gameResult->compareScoresWithPushRule($this->player, 20, 20, 0);
        $this->assertEquals(1000, $this->player->getBankroll());
    }

    private function createBlackjackHand(): ProjHand
    {
        $hand = new ProjHand();
        $hand->addCard(new Proj('hearts', 'ace'));
        $hand->addCard(new Proj('diamonds', 'king'));
        return $hand;
    }

    private function createHand(array $values): ProjHand
    {
        $hand = new ProjHand();
        foreach ($values as $value) {
            $hand->addCard(new Proj('hearts', $value));
        }
        return $hand;
    }
}