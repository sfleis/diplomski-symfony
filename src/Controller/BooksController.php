<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    #[Route('/books', methods: ['GET'], name: 'books_list')]
    public function index(): Response 
    {
        $books = $this->bookRepository->findAll(); 

        return $this->render('books/index.html.twig', [
            'books' => $books, 
        ]);
    }

    #[Route('/books/{id}', methods: ['GET'], name: 'books_show')]
    public function show($id): Response 
    {
        $book = $this->bookRepository->find($id); 
        
        return $this->render('books/show.html.twig', [
            'book' => $book, 
        ]);
    }
}
