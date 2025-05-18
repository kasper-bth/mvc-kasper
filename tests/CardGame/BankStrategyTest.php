<?php

namespace App\Tests\Card;

use App\Card\BankStrategy;
use PHPUnit\Framework\TestCase;

class BankStrategyTest extends TestCase
{
    public function testBankStrategyEdgeCases(): void
    {
        $strategy = new BankStrategy();
        
        $this->assertTrue($strategy->shouldDraw(16, 20));
        
        $this->assertFalse($strategy->shouldDraw(17, 20));
        
        $this->assertFalse($strategy->shouldDraw(16, 22));
        
        $this->assertFalse($strategy->shouldDraw(21, 20));
    }
}