<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Contribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadContribution form.
 */
class ContributionFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Contribution();
            $fixture->setWork($this->getReference('work.' . $i));
            $fixture->setRole($this->getReference('role.' . $i));
            $fixture->setPerson($this->getReference('person.' . $i));

            $em->persist($fixture);
            $this->setReference('contribution.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return [
            WorkFixtures::class,
            RoleFixtures::class,
            PersonFixtures::class,
        ];
    }
}
