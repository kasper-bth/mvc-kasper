<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;


class ProjHandTest extends TestCase
{
    public function testCreateEmptyHand(): void
    {
        $hand = new ProjHand();
        $this->assertInstanceOf('\App\Proj\ProjHand', $hand);
        $this->assertCount(0, $hand->getCards());
    }

    public function testAddCard(): void
    {
        $hand = new ProjHand();
        $card = new Proj('hearts', 'ace');
        $hand->addCard($card);
        
        $this->assertCount(1, $hand->getCards());
        $this->assertEquals($card, $hand->getCards()[0]);
    }

    public function testGetNumberCards(): void
    {
        $hand = new ProjHand();
        $hand->addCard(new Proj('diamonds', 'king'));
        $hand->addCard(new Proj('spades', 'queen'));
        
        $this->assertEquals(2, $hand->getNumberCards());
    }

    public function testGetValues(): void
    {
        $hand = new ProjHand();
        $hand->addCard(new Proj('clubs', '10'));
        $hand->addCard(new Proj('hearts', 'jack'));
        
        $values = $hand->getValues();
        $this->assertEquals(['10', 'jack'], $values);
    }

    public function testGetString(): void
    {
        $hand = new ProjHand();
        $hand->addCard(new Proj('diamonds', 'ace'));
        $hand->addCard(new Proj('spades', '2'));
        
        $strings = $hand->getString();
        $this->assertEquals(['ace of diamonds', '2 of spades'], $strings);
    }
}