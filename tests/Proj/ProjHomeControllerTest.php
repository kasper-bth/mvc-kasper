<?php

namespace App\Proj;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjHomeControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/proj');
        
        $this->assertResponseIsSuccessful();
    }

    public function testAboutPage(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/proj/about');
        
        $this->assertResponseIsSuccessful();
    }
}