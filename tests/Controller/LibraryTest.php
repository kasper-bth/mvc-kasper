<?php

namespace App\Tests\Entity;

use App\Entity\Library;
use PHPUnit\Framework\TestCase;

class LibraryTest extends TestCase
{
    public function testEntity(): void
    {
        $book = new Library();
        $book->setTitle('Test Book');
        $book->setAuthor('Test Author');
        $book->setISBN('1234567890');
        $book->setImg('test.jpg');
        
        $this->assertEquals('Test Book', $book->getTitle());
        $this->assertEquals('Test Author', $book->getAuthor());
        $this->assertEquals('1234567890', $book->getISBN());
        $this->assertEquals('test.jpg', $book->getImg());
    }
}