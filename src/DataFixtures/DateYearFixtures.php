<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\DateYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadDateYear form.
 */
class DateYearFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new DateYear();
            $fixture->setValue(1900 + $i);
            $fixture->setDatecategory($this->getReference('datecategory.1'));
            $fixture->setWork($this->getReference('work.1'));

            $em->persist($fixture);
            $this->setReference('dateyear.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        return [
            DateCategoryFixtures::class,
            WorkFixtures::class,
        ];
    }
}
