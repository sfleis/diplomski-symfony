<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author = new Author();
        $author->setName("Jane Austen");
        $manager->persist($author);

        $author2 = new Author();
        $author2->setName("Neil Gaiman"); 
        $manager->persist($author2);

        $author3 = new Author();
        $author3->setName("Terry Pratchett"); 
        $manager->persist($author3);

        // Add references before flushing
        $this->addReference('author_1', $author);
        $this->addReference('author_2', $author2);
        $this->addReference('author_3', $author3);

        $manager->flush();
    }
}
