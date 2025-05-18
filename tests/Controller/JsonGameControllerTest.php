<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Card\CardGame;
use App\Card\DeckOfCards;

class JsonGameControllerTest extends WebTestCase
{
    public function testJsonGameScoreWithGame(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/game/play');
        
        $client->request('GET', '/api/game');
        
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('player_score', $response);
        $this->assertArrayHasKey('bank_score', $response);
    }

    public function testJsonGameScoreWithoutGame(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/game');
        
        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($client->getResponse()->getContent());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('No game in progress', $response['error']);
    }
}