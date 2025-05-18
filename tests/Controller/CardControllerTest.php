<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Card');
    }

    public function testDeckPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card-container');
    }

    public function testShufflePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.container');
    }

    public function testDrawCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card');
    }
}