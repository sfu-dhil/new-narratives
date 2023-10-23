<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Place();
            $fixture->setName('Name ' . $i);
            $fixture->setState('State ' . $i);
            $fixture->setCountry('Country ' . $i);

            $em->persist($fixture);
            $this->setReference('place.' . $i, $fixture);
        }
        $em->flush();
    }
}
