<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardGameControllerTest extends WebTestCase
{
    public function testGameHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');
        
        $this->assertResponseIsSuccessful();
    }

    public function testGamePlay(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/play');
        
        $this->assertResponseIsSuccessful();
    }

    public function testGameDraw(): void
    {
        $client = static::createClient();
        $client->request('POST', '/game/draw');
        
        $this->assertResponseRedirects('/game/play');
    }
}