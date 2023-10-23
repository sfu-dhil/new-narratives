<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadPublisher form.
 */
class PublisherFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Publisher();
            $fixture->setName('Name ' . $i);

            $em->persist($fixture);
            $this->setReference('publisher.' . $i, $fixture);
        }

        $em->flush();
    }
}
