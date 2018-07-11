<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadGenre form.
 */
class LoadGenre extends Fixture
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
