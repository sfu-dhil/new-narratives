<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Person();
            $fixture->setFullName('FullName ' . $i);
            $fixture->setBirthDate('1800-01-' . $i);
            $fixture->setDeathDate('1850-01-' . $i);
            $fixture->setBiography("<p>This is paragraph {$i}</p>");
            $fixture->setBirthplace($this->getReference('place.' . $i));
            $fixture->setDeathplace($this->getReference('place.' . $i));
            $fixture->addResidence($this->getReference('place.' . $i));
            $em->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }
        $em->flush();
    }

    public function getDependencies() {
        return [
            PlaceFixtures::class,
            PlaceFixtures::class,
        ];
    }
}
