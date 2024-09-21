<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $book = new Book();
        $book->setTitle("Pride and Prejudice");
        $book->setDescription("Pride and Prejudice, romantic novel by Jane Austen, published anonymously in three volumes");
        $book->setReleaseYear(1813);
        $book->setImagePath('https://cdn.pixabay.com/photo/2020/07/21/17/48/love-5426977_640.jpg');
        $book->addAuthor($this->getReference('author_1'));
        $manager->persist($book);

        $book2 = new Book();
        $book2->setTitle("Good Omens");
        $book2->setDescription("Novel written as a collaboration between the English authors Terry Pratchett and Neil Gaiman");
        $book2->setReleaseYear(1990);
        $book2->setImagePath('https://cdn.pixabay.com/photo/2017/04/05/08/24/good-2204244_640.png');
        $book2->addAuthor($this->getReference('author_2'));
        $book2->addAuthor($this->getReference('author_3'));
        $manager->persist($book2);

        $manager->flush();
        }
}
