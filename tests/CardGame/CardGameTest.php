<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGame.
 */
class CardGameTest extends TestCase
{
    public function testCreateCardGame(): void
    {
        $deck = $this->createMock(DeckOfCards::class);
        $game = new CardGame($deck);

        $this->assertInstanceOf("\App\Card\CardGame", $game);
        $this->assertFalse($game->isGameOver());
        $this->assertEquals(0, $game->getPlayerScore());
        $this->assertEquals(0, $game->getBankScore());
    }

    public function testPlayerDraw(): void
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '5')
            );

        $game = new CardGame($deck);

        $game->playerDraw($deck);
        $this->assertCount(1, $game->getPlayerHand()->getCards());
        $this->assertGreaterThan(0, $game->getPlayerScore());
        $this->assertFalse($game->isGameOver());

        $game->playerDraw($deck);
        $this->assertCount(2, $game->getPlayerHand()->getCards());
        $this->assertEquals(15, $game->getPlayerScore());
    }

    public function testPlayerBust(): void
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '10'),
                new Card('clubs', '5')
            );

        $game = new CardGame($deck);
        
        $game->playerDraw($deck);
        $game->playerDraw($deck);
        $game->playerDraw($deck);
        
        $this->assertTrue($game->isGameOver());
        $this->assertEquals(25, $game->getPlayerScore());
    }

    public function testPlayerStopTriggersBankPlay(): void
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '7'),
                new Card('clubs', '10'),
                new Card('spades', '9')
            );

        $game = new CardGame($deck);
        
        $game->playerDraw($deck);
        $game->playerDraw($deck);
        $game->playerStop($deck);
        
        $this->assertTrue($game->isGameOver());
        $this->assertEquals(19, $game->getBankScore());
    }

    public function testScoreCalculationThroughGameplay(): void
    {
        $deck1 = $this->createMock(DeckOfCards::class);
        $deck1->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '7'),
                new Card('diamonds', '5')
            );

        $game1 = new CardGame($deck1);
        $game1->playerDraw($deck1);
        $game1->playerDraw($deck1);
        $this->assertEquals(12, $game1->getPlayerScore());

        $deck2 = $this->createMock(DeckOfCards::class);
        $deck2->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', 'jack'),
                new Card('diamonds', 'queen')
            );

        $game2 = new CardGame($deck2);
        $game2->playerDraw($deck2);
        $game2->playerDraw($deck2);
        $this->assertEquals(23, $game2->getPlayerScore());

        $deck3 = $this->createMock(DeckOfCards::class);
        $deck3->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', 'ace'),
                new Card('diamonds', '7')
            );

        $game3 = new CardGame($deck3);
        $game3->playerDraw($deck3);
        $game3->playerDraw($deck3);
        $this->assertEquals(8, $game3->getPlayerScore());

        $deck4 = $this->createMock(DeckOfCards::class);
        $deck4->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', 'ace'),
                new Card('diamonds', '2')
            );

        $game4 = new CardGame($deck4);
        $game4->playerDraw($deck4);
        $game4->playerDraw($deck4);
        $this->assertEquals(16, $game4->getPlayerScore());

        $deck5 = $this->createMock(DeckOfCards::class);
        $deck5->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', 'ace'),
                new Card('diamonds', 'ace'),
                new Card('clubs', 'ace'),
                new Card('clubs', '3')
            );

        $game5 = new CardGame($deck5);
        $game5->playerDraw($deck5);
        $game5->playerDraw($deck5);
        $game5->playerDraw($deck5);
        $game5->playerDraw($deck5);
        $this->assertEquals(19, $game5->getPlayerScore());
    }

    public function testGetBankHand(): void
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')
            ->willReturn(new Card('hearts', '10'));

        $game = new CardGame($deck);
        $game->playerDraw($deck);
        $game->playerStop($deck);

        $bankHand = $game->getBankHand();
        $this->assertInstanceOf(CardHand::class, $bankHand);
        $this->assertGreaterThan(0, count($bankHand->getCards()));
    }

    public function testAllWinnerScenarios(): void
    {
        $deck1 = $this->createMock(DeckOfCards::class);
        $deck1->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '10'),
                new Card('clubs', '5')
            );

        $game1 = new CardGame($deck1);
        $game1->playerDraw($deck1);
        $game1->playerDraw($deck1);
        $game1->playerDraw($deck1);
        $this->assertEquals('bank', $game1->getWinner());

        $deck2 = $this->createMock(DeckOfCards::class);
        $deck2->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '7'),
                new Card('clubs', '10'),
                new Card('spades', '13')
            );

        $game2 = new CardGame($deck2);
        $game2->playerDraw($deck2);
        $game2->playerDraw($deck2);
        $game2->playerStop($deck2);

        $this->assertGreaterThan(21, $game2->getBankScore());
        $this->assertEquals('player', $game2->getWinner());

        $deck3 = $this->createMock(DeckOfCards::class);
        $deck3->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '8'),
                new Card('clubs', '10'),
                new Card('spades', '9')
            );

        $game3 = new CardGame($deck3);
        $game3->playerDraw($deck3);
        $game3->playerDraw($deck3);
        $game3->playerStop($deck3);
        $this->assertEquals('bank', $game3->getWinner());

        $deck4 = $this->createMock(DeckOfCards::class);
        $deck4->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '10'),
                new Card('clubs', '10'),
                new Card('spades', '9')
            );

        $game4 = new CardGame($deck4);
        $game4->playerDraw($deck4);
        $game4->playerDraw($deck4);
        $game4->playerStop($deck4);
        $this->assertEquals('player', $game4->getWinner());

        $deck5 = $this->createMock(DeckOfCards::class);
        $deck5->method('drawCard')
            ->willReturnOnConsecutiveCalls(
                new Card('hearts', '10'),
                new Card('diamonds', '10'),
                new Card('clubs', '10'),
                new Card('spades', '10')
            );

        $game5 = new CardGame($deck5);
        $game5->playerDraw($deck5);
        $game5->playerDraw($deck5);
        $game5->playerStop($deck5);
        $this->assertEquals('bank', $game5->getWinner());
    }
}