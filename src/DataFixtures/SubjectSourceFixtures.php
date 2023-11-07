<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\SubjectSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadSubjectSource form.
 */
class SubjectSourceFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new SubjectSource();
            $fixture->setName('source_' . $i);
            $fixture->setLabel('Source ' . $i);
            $fixture->setDescription('source description ' . $i);

            $em->persist($fixture);
            $this->setReference('subjectsource.' . $i, $fixture);
        }

        $em->flush();
    }
}
