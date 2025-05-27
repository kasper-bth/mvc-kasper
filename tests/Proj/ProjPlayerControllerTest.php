<?php

namespace App\Proj;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjPlayerControllerTest extends WebTestCase
{
    public function testConfigRedirectsWhenNoPlayer(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/proj/config');
        
        $this->assertResponseRedirects('/proj');
    }

    public function testUpdateConfig(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('POST', '/proj/update-config', [
            'hands' => 2,
            'bet' => 20
        ]);
        
        $this->assertResponseRedirects('/proj/game');
    }
}