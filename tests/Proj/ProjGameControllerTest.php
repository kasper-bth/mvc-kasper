<?php

namespace App\Proj;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjGameControllerTest extends WebTestCase
{
    public function testGameRedirectsWhenNoSession(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/proj/game');
        $this->assertResponseRedirects('/proj');
    }

    public function testGameRendersTemplateWithGameData(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/game');
        $this->assertResponseIsSuccessful();
    }

    public function testInitGameWithNewPlayer(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $this->assertResponseRedirects('/proj/game');
    }

    public function testDrawCard(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/draw');
        $this->assertResponseRedirects('/proj/game');
    }

    public function testStand(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/stand');
        $this->assertResponseRedirects('/proj/game');
    }

    public function testNewRound(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/stand');
        
        $client->request('POST', '/proj/new-round', [
            'hands' => 1,
            'bet' => 10
        ]);
        
        $this->assertResponseRedirects('/proj/game');
    }

    public function testNewRoundWithInvalidBet(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/stand');
        
        $client->request('POST', '/proj/new-round', [
            'hands' => 1,
            'bet' => 9999
        ]);
        
        $this->assertResponseRedirects('/proj/config');
    }
}