<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadGenre form.
 */
class GenreFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
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
