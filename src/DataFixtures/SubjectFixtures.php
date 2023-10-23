<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadSubject form.
 */
class SubjectFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Subject();
            $fixture->setName('subject_' . $i);
            $fixture->setLabel('Subject ' . $i);
            $fixture->setDescription('Subject description ' . $i);
            $fixture->setSubjectsource($this->getReference('subjectsource.1'));

            $em->persist($fixture);
            $this->setReference('subject.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return [
            SubjectSourceFixtures::class,
        ];
    }
}
