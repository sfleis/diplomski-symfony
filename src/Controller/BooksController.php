<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    private $em;
    private $bookRepository;

    public function __construct(BookRepository $bookRepository, EntityManagerInterface $em)
    {
        $this->bookRepository = $bookRepository;
        $this->em = $em;
    }

    #[Route('/books', methods: ['GET'], name: 'books')]
    public function index(): Response 
    {
        $books = $this->bookRepository->findAll(); 

        return $this->render('books/index.html.twig', [
            'books' => $books, 
        ]);
    }

    #[Route('/books/create', name: 'create_book')]
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newBook = $form->getData();

            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newBook->setImagePath('/uploads/' . $newFileName); // Changed to setImagePath
            }

            $this->em->persist($newBook);
            $this->em->flush();

            return $this->redirectToRoute('books');
        }

        return $this->render('books/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books/edit/{id}', name: 'edit_book')]
    public function edit($id, Request $request): Response 
    {
        $book = $this->bookRepository->find($id);

        $form = $this->createForm(BookFormType::class, $book);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                if ($book->getImagePath() !== null) {
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . $book->getImagePath()
                        )) {
                            $this->GetParameter('kernel.project_dir') . $book->getImagePath();
                    }
                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                    try {
                        $imagePath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $book->setImagePath('/uploads/' . $newFileName);
                    $this->em->flush();

                    return $this->redirectToRoute('books');
                }
            } else {
                $book->setTitle($form->get('title')->getData());
                $book->setReleaseYear($form->get('releaseYear')->getData());
                $book->setDescription($form->get('description')->getData());

                $this->em->flush();
                return $this->redirectToRoute('books');
            }
        }

        return $this->render('books/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    #[Route('/books/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_book')]
    public function delete($id): Response
    {
        $book = $this->bookRepository->find($id);
        $this->em->remove($book);
        $this->em->flush();

        return $this->redirectToRoute('books');
    }


    #[Route('/books/{id}', methods: ['GET'], name: 'books_show')]
    public function show($id): Response 
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }

        return $this->render('books/show.html.twig', [
            'book' => $book, 
        ]);
    }
}
