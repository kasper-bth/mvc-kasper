<?php

namespace App\Proj;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjHomeControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj');
        
        $this->assertRouteSame('proj_home');
    }

    public function testAboutPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/about');
        
        $this->assertRouteSame('proj_about');
    }
}