<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(LibraryRepository $libraryRepository): Response
    {
        $books = $libraryRepository->findAll();
        
        return $this->render('library/index.html.twig', [
            'books' => $books,
            'controller_name' => 'LibraryController'
        ]);
    }

    #[Route('/library/create', name: 'library_create', methods: ['GET', 'POST'])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {
            $book = new Library();
            $book->setTitle($request->request->get('title'));
            $book->setAuthor($request->request->get('author'));
            $book->setISBN($request->request->get('isbn'));
            $book->setImg($request->request->get('img'));

            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_library');
        }

        return $this->render('library/create.html.twig');
    }

    #[Route('/library/show/{id}', name: 'library_show', methods: ['GET'])]
    public function show(Library $book): Response
    {
        return $this->render('library/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Library $book, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {
            $book->setTitle($request->request->get('title'));
            $book->setAuthor($request->request->get('author'));
            $book->setISBN($request->request->get('isbn'));
            $book->setImg($request->request->get('img'));

            $doctrine->getManager()->flush();
            return $this->redirectToRoute('app_library');
        }

        return $this->render('library/update.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function delete(Request $request, Library $book, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_library');
    }
}
