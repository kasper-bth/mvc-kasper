<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\LibraryRepository;

class JsonDatabaseController extends AbstractController
{
    #[Route("/api/library/books", name: "api_library_books", methods: ['GET'])]
    public function jsonLibraryBooks(LibraryRepository $libraryRepository): Response
    {
        $books = $libraryRepository->findAll();

        $booksArray = array_map(function ($book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getISBN(),
                'image' => $book->getImg()
            ];
        }, $books);

        $data = [
            'books' => $booksArray,
            'count' => count($booksArray)
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/book/{isbn}", name: "api_library_book", methods: ['GET'])]
    public function jsonLibraryBookByIsbn(LibraryRepository $libraryRepository, string $isbn): Response
    {
        $book = $libraryRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return new JsonResponse([
                'error' => 'Book not found',
                'message' => 'No book found with ISBN ' . $isbn
            ], 404);
        }

        $data = [
            'book' => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getISBN(),
                'image' => $book->getImg()
            ]
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
