<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadRole form.
 */
class RoleFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Role();
            $fixture->setName('role_' . $i);
            $fixture->setLabel('Role ' . $i);
            $fixture->setDescription('role description ' . $i);

            $em->persist($fixture);
            $this->setReference('role.' . $i, $fixture);
        }

        $em->flush();
    }
}
