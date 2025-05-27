<?php

namespace App\Proj;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjResetControllerTest extends WebTestCase
{
    public function testReset(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/reset');
        $this->assertResponseRedirects('/proj');
        
        $client->request('GET', '/proj/game');
        $this->assertResponseRedirects('/proj');
    }

    public function testFullReset(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('POST', '/proj/init', [
            'nickname' => 'testplayer',
            'hands' => 1,
            'bet' => 10
        ]);
        
        $client->request('GET', '/proj/full-reset');
        $this->assertResponseRedirects('/proj');
        
        $client->request('GET', '/proj/game');
        $this->assertResponseRedirects('/proj');
    }
}