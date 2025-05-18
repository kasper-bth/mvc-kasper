<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsonDeckControllerTest extends WebTestCase
{
    public function testJsonDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(52, $response['deck']);
    }

    public function testJsonShuffle(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');
        
        $this->assertResponseIsSuccessful();
        $this->assertCount(52, json_decode($client->getResponse()->getContent(), true)['deck']);
    }
}