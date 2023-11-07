<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadRole form.
 */
class RoleFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Role();
            $fixture->setName('role_' . chr(ord('a') + $i));
            $fixture->setLabel('Role ' . $i);
            $fixture->setDescription('role description ' . $i);

            $em->persist($fixture);
            $this->setReference('role.' . $i, $fixture);
        }

        $em->flush();
    }
}
