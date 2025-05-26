<?php

namespace App\Proj;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjHomeControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Blackjack');
    }

    public function testAboutPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/about');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'About');
    }
}