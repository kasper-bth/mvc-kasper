<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class JsonDatabaseControllerTest extends WebTestCase
{
    public function testJsonLibraryBooks(): void
    {
        $client = static::createClient();
        
        $book = new Library();
        $book->setTitle('Test Book');
        $book->setAuthor('Test Author');
        $book->setISBN('1234567890123');
        $book->setImg('test.jpg');
        
        $bookRepository = $this->createMock(LibraryRepository::class);
        $bookRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$book]);
            
        $client->getContainer()->set(LibraryRepository::class, $bookRepository);
        
        $client->request('GET', '/api/library/books');
        
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Test Book', $response['books'][0]['title']);
    }

    public function testJsonLibraryBookByIsbn(): void
    {
        $client = static::createClient();
        
        $book = new Library();
        $book->setTitle('Test Book');
        $book->setAuthor('Test Author');
        $book->setISBN('1234567890123');
        $book->setImg('test.jpg');
        
        $bookRepository = $this->createMock(LibraryRepository::class);
        $bookRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['isbn' => '1234567890123'])
            ->willReturn($book);
            
        $client->getContainer()->set(LibraryRepository::class, $bookRepository);
        
        $client->request('GET', '/api/library/book/1234567890123');
        
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Test Book', $response['book']['title']);
    }

    public function testJsonLibraryBookByIsbnNotFound(): void
    {
        $client = static::createClient();
        
        $bookRepository = $this->createMock(LibraryRepository::class);
        $bookRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['isbn' => '0000000000000'])
            ->willReturn(null);
            
        $client->getContainer()->set(LibraryRepository::class, $bookRepository);
        
        $client->request('GET', '/api/library/book/0000000000000');
        
        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($client->getResponse()->getContent());
    }
}