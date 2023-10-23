<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadGenre form.
 */
class GenreFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

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
