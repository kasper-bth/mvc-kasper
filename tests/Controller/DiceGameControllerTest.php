<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Dice\DiceHand;

class DiceGameControllerTest extends WebTestCase
{
    public function testHomeRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig');

        $this->assertResponseIsSuccessful();
    }

    public function testTestRollDice(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/roll');

        $this->assertResponseIsSuccessful();
    }

    public function testTestRollNumDices(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/roll/3');

        $this->assertResponseIsSuccessful();
    }

    public function testTestRollNumDicesMaxLimit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/roll/100');

        $this->assertResponseStatusCodeSame(500);
    }

    public function testTestDiceHand(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/test/dicehand/3');

        $this->assertResponseIsSuccessful();
    }

    public function testInitGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/pig/init');

        $this->assertResponseIsSuccessful();
    }

    public function testInitPost(): void
    {
        $client = static::createClient();
        $client->request('POST', '/game/pig/init', ['num_dices' => 2]);

        $this->assertResponseRedirects('/game/pig/play');
    }

    public function testPlay(): void
    {
        $client = static::createClient();
        
        $client->request('POST', '/game/pig/init', ['num_dices' => 1]);
        $client->request('GET', '/game/pig/play');

        $this->assertResponseIsSuccessful();
    }

    public function testSave(): void
    {
        $client = static::createClient();
        
        $client->request('POST', '/game/pig/init', ['num_dices' => 1]);
        
        $client->request('POST', '/game/pig/roll');
        $client->request('POST', '/game/pig/save');

        $this->assertResponseRedirects('/game/pig/play');
    }
}