<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

class ProjHandManagerTest extends TestCase
{
    public function testInitialState(): void
    {
        $manager = new ProjHandManager();
        $this->assertCount(1, $manager->getAllHands());
        $this->assertEquals(0, $manager->getCurrentIndex());
    }

    public function testAddHand(): void
    {
        $manager = new ProjHandManager();
        $this->assertTrue($manager->addHand());
        $this->assertCount(2, $manager->getAllHands());
        
        $this->assertTrue($manager->addHand());
        $this->assertCount(3, $manager->getAllHands());
        
        $this->assertFalse($manager->addHand());
    }

    public function testNextHand(): void
    {
        $manager = new ProjHandManager();
        $manager->addHand();
        
        $this->assertTrue($manager->nextHand());
        $this->assertEquals(1, $manager->getCurrentIndex());
        
        $this->assertFalse($manager->nextHand());
    }
}