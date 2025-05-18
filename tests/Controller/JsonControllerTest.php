<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsonControllerTest extends WebTestCase
{
    public function testJsonLucky(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/lucky');
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('lucky-number', $response);
        $this->assertArrayHasKey('lucky-message', $response);
    }

    public function testJsonQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('quotes', $response);
        $this->assertArrayHasKey('date', $response);
    }
}