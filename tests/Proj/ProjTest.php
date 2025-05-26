<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjTest extends TestCase
{
    public function testProjCreation(): void
    {
        $card = new Proj('hearts', 'ace');
        $this->assertInstanceOf('\App\Proj\Proj', $card);
    }

    public function testGetSuit(): void
    {
        $card = new Proj('diamonds', 'king');
        $this->assertEquals('diamonds', $card->getSuit());
    }

    public function testGetValue(): void
    {
        $card = new Proj('spades', 'queen');
        $this->assertEquals('queen', $card->getValue());
    }

    public function testGetAsString(): void
    {
        $card = new Proj('clubs', '10');
        $this->assertEquals('10 of clubs', $card->getAsString());
    }

    public function testMatches(): void
    {
        $card = new Proj('hearts', 'ace');
        $this->assertTrue($card->matches('hearts', 'ace'));
        $this->assertFalse($card->matches('diamonds', 'ace'));
    }
}