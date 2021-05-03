<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
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
