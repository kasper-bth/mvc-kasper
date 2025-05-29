<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class BettingTest extends TestCase
{
    private Player $player;
    private GameResult $gameResult;
    private ScoreCalculator $calculator;

    protected function setUp(): void
    {
        $this->player = new Player('test');
        $this->gameResult = new GameResult();
        $this->calculator = new ScoreCalculator();
    }

    public function testRegularWinPayout(): void
    {
        $this->player->placeBet(100);
        $hand = $this->createHand(['10', '7']);
        $bankHand = $this->createHand(['10', '6']);
        
        $this->gameResult->evaluateResults(
            $this->player,
            [$hand],
            $bankHand,
            false
        );
        
        $this->assertEquals(1100, $this->player->getBankroll());
        $this->assertEquals(100, $this->player->getTotalWins());
    }

    public function testBlackjackPayout(): void
    {
        $this->player->placeBet(100);
        $hand = $this->createHand(['ace', 'king']);
        $bankHand = $this->createHand(['10', '7']);
        
        $this->gameResult->evaluateResults(
            $this->player,
            [$hand],
            $bankHand,
            true
        );
        
        $this->assertEquals(1150, $this->player->getBankroll());
        $this->assertEquals(150, $this->player->getTotalWins());
    }

    public function testLossPayout(): void
    {
        $this->player->placeBet(100);
        $hand = $this->createHand(['10', '7']);
        $bankHand = $this->createHand(['10', '8']);
        
        $this->gameResult->evaluateResults(
            $this->player,
            [$hand],
            $bankHand,
            false
        );
        
        $this->assertEquals(900, $this->player->getBankroll());
        $this->assertEquals(100, $this->player->getTotalLosses());
    }

    public function testPushPayout(): void
    {
        $this->player->placeBet(100);
        $hand = $this->createHand(['10', '10']);
        $bankHand = $this->createHand(['10', '10']);
        
        $this->gameResult->evaluateResults(
            $this->player,
            [$hand],
            $bankHand,
            false
        );
        
        $this->assertEquals(1000, $this->player->getBankroll());
        $this->assertEquals(0, $this->player->getTotalWins());
        $this->assertEquals(0, $this->player->getTotalLosses());
    }

    public function testPushRuleForBank(): void
    {
        $this->player->placeBet(100);
        $hand = $this->createHand(['10', '7']);
        $bankHand = $this->createHand(['10', '7']);
        
        $this->gameResult->compareScoresWithPushRule(
            $this->player,
            $this->calculator->calculate($hand),
            $this->calculator->calculate($bankHand),
            0
        );
        
        $this->assertEquals(900, $this->player->getBankroll());
        $this->assertEquals(100, $this->player->getTotalLosses());
    }

    public function testMultipleHandOutcomes(): void
    {
        $this->player->placeBet(100, 0);
        $this->player->placeBet(100, 1);
        
        $hand1 = $this->createHand(['10', '7']);
        $hand2 = $this->createHand(['ace', 'king']);
        $bankHand = $this->createHand(['10', '8']);
        
        $this->gameResult->evaluateResults(
            $this->player,
            [$hand1, $hand2],
            $bankHand,
            true
        );
        
        $this->assertEquals(1050, $this->player->getBankroll());
        $this->assertEquals(150, $this->player->getTotalWins());
        $this->assertEquals(100, $this->player->getTotalLosses());
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