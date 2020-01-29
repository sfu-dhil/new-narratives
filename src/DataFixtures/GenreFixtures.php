<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadGenre form.
 */
class GenreFixtures extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Genre();
            $fixture->setName('genre_' . $i);
            $fixture->setLabel('Genre ' . $i);
            $fixture->setDescription('genre description ' . $i);
            $em->persist($fixture);
            $this->setReference('genre.' . $i, $fixture);
        }

        $em->flush();

    }

}
